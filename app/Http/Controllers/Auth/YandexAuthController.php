<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Project;
use App\Models\ServiceType;
use App\Models\YandexAccess;
use App\Models\YandexBusinessCounters;
use App\Models\YandexDirectCampaign;
use App\Models\YandexDirectCampaigns;
use App\Models\YandexDirectStatistic;
use App\Models\YandexDirectStatistics;
use App\Services\Auth\YandexAuthService;
use App\Services\YandexDirectStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class YandexAuthController extends Controller
{
    public function __construct(
        private YandexAuthService $authService,
        private YandexDirectStatisticsService $directStatsService,
    ) {}

    public function callback(Request $request)
    {
        $stateData = Cache::get("oauth:{$request->state}");

        if (!$stateData) {
            abort(400, 'Неверный state параметр');
        }
        $serviceType = ServiceType::from($stateData['service_type']);

        $tokens = $this->authService->handleCallback($request->all());
        $accountData = $this->authService->getAccountInfo($serviceType, $tokens['access_token']);

        $account = Account::create([
            'project_id' => $stateData['project_id'],
            'name' => $accountData['account_name'],
            'type' => $accountData['service_type'],
            'amount' => $accountData['amount'] ?? 0,
            'metadata' => '{}'
        ]);

        $accountAccess = YandexAccess::updateOrCreate([
            'account_id' => $account->id,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
        ],[
            'account_id' => $account->id,
            'client_login' => $account->name,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token'],
            'token_expires_at' => now()->addSeconds($tokens['expires_in']),
            'is_active' => true,
            'metadata' => json_encode($tokens)
        ]);

        $this->fetchInitials($account, $accountAccess);

        $redirectDestination = 'projects.index';
        $params = ['project' => $account->project_id];

        return redirect()->route($redirectDestination, $params)
            ->with('success', ' успешно подключен к аккаунту "' . $account->name . '"!');
    }

    private function fetchInitials(Account $account, YandexAccess $access): void
    {
        try {
            if ($account->type === ServiceType::YANDEX_DIRECT->value) {
                $statistics = $this->directStatsService->fetchAllTimeStatistics($access->access_token);

                $statisticsCollection = collect($statistics);

                //получаем здесь кампании из статистики, это сделано, чтобы получать товарные кампании, они не приходят по запросу кампаний отдельно
                //можно сделать и получение сначала кампаний затем по их идентификаторам статистику за период
                $uniqueCampaignsData = $statisticsCollection->map(function($statistics){
                    return [
                        'id' => $statistics['CampaignId'],
                        'name' => $statistics['CampaignName']
                    ];
                })->unique('id');

                $uniqueCampaignsData->each(function($data) use ($account){
                    YandexDirectCampaign::updateOrCreate(
                        [
                            'campaign_id' => (string) $data['id'],
                            'account_id' => $account->id
                        ],
                        [
                            'campaign_name' => $data['name'],
                        ]
                    );
                });

                collect($statistics)->chunk(100)->each(function($chank) use ($account){
                    $batchData = [];

                    foreach ($chank as $row){
                        if (empty($row['Date'])) continue;

                        $batchData[] = [
                            'campaign_id' => $row['CampaignId'],
                            'date' => $row['Date'],
                            'impressions' => $row['Impressions'] ?? 0,
                            'clicks' => $row['Clicks'] ?? 0,
                            'ctr' => $row['Ctr'] ?? 0,
                            'avg_cpc' => $row['AvgCpc'] ?? 0,
                            'cost' => $row['Cost'] ?? 0,
                            'conversions' => $row['Conversions'] ?? 0,
                            'cost_per_conversion' => $row['CostPerConversion'] ?? 0,
                            'conversion_rate' => ($row['Conversions'] && $row['Clicks'])
                                ? ($row['Conversions'] / $row['Clicks']) * 100
                                : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    if(!empty($batchData)){
                        YandexDirectStatistic::insert($batchData);
                    }
                });
            }
        } catch (\Exception $e) {
            \Log::error('Initials fetch failed', [
                'account_id' => $account->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
