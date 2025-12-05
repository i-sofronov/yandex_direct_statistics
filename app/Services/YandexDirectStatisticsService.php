<?php
namespace App\Services;

use App\Http\Clients\YandexDirectClient;
use App\Models\Account;
use Carbon\Carbon;

class YandexDirectStatisticsService
{
    public function __construct(
        private YandexDirectClient $client
    ) {}

    public function fetchCampaigns($accountAccessToken, $campaignsIds): array
    {
        $this->client->setAccessToken($accountAccessToken);

        return $this->client->getCampaigns(null, $campaignsIds);
    }

    public function fetchStatistics($accountAccessToken, Carbon $from, Carbon $to): array
    {
        $this->client->setAccessToken($accountAccessToken);

        return $this->client->getStatisticsByDateRange($from, $to);
    }

    public function fetchAllTimeStatistics($accountAccessToken): array
    {
        $this->client->setAccessToken($accountAccessToken);

        return $this->client->getAllTimeStatistics();
    }

    public function testConnection(string $accountAccessToken): bool
    {
        try {
            $this->client->setAccessToken($accountAccessToken);
            $this->client->getAccountInfo();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function aggregateStatistics(array $statistics, array $fields = [
        'clicks',
        'impressions',
        'ctr',
        'conversions',
        'cpc',
        'cpa',
        'conversion_rate',
    ]){

        $total = [];
        $totalFields = [];
        $necessaryFields = [
            'ctr' => [
                'clicks', 'impressions'
            ],
            'cpc' => [
                'cost', 'conversions'
            ],
            'cpa' => [
                'cost', 'clicks'
            ],
            'conversion_rate' => [
                'conversions', 'clicks'
            ],
        ];

        foreach ($fields as $field){
            if(isset($necessaryFields[$field])){
                $totalFields = array_unique(array_merge($totalFields, $necessaryFields[$field], [$field]));
            }
            else{
                if(!in_array($field, $totalFields)){
                    $totalFields []= $field;
                }
            }
        }

        foreach ($totalFields as $field){
            if (in_array($field, ['ctr', 'cpc', 'cpa', 'conversion_rate'])) continue;

            $total[$field] = array_reduce($statistics, function($a, $b) use ($field){
                return $a += $b[$field];
            }, 0);

            if(is_float($total[$field])){
                $total[$field] = round($total[$field], 2);
            }
        }

        if(in_array('ctr', $totalFields)){
            $total['ctr'] = $this->getCTR($total['clicks'], $total['impressions']);
        }

        if(in_array('cpc', $totalFields)){
            $total['cpc'] = $this->getCPC($total['cost'], $total['conversions']);
        }

        if(in_array('cpa', $totalFields)){
            $total['cpa'] = $this->getCPA($total['cost'], $total['clicks']);
        }

        if(in_array('conversion_rate', $totalFields)){
            $total['conversion_rate'] = $this->getConversionsRate($total['conversions'], $total['clicks']);
        }

        return $total;
    }

    public function getCTR(int $clicks, int $impressions){
        if($impressions === 0){
            return 0;
        }

        return round(($clicks / $impressions) * 100, 2);
    }

    public function getCPC(float $cost, int $conversions){
        if($conversions === 0){
            return 0;
        }

        return round($cost / $conversions, 2);
    }

    public function getCPA(float $cost, int $clicks){
        if($clicks === 0){
            return 0;
        }

        return round($cost / $clicks, 2);
    }

    public function getConversionsRate(int $conversions, int $clicks){
        if($clicks === 0){
            return 0;
        }

        return round(($conversions / $clicks) * 100, 2);
    }


}
