<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Project;
use App\Services\Auth\YandexAuthService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StatisticsService
{

    public function __construct(
        private YandexAuthService $authService,
        private YandexDirectStatisticsService $yandexDirectStatisticsService,
    ) {}


    public function getProjectsStatistics(array $filters = []): Collection
    {

        [$startDate, $endDate, $prevStart, $prevEnd] = $this->parsePeriodFilters($filters);

        $projects = Project::with(['accounts'])->get();

        return $this->processProjectsStatistics($projects, $startDate, $endDate, $prevStart, $prevEnd);
    }

    public function getProjectAccountsStatistics(Project $project, array $filters = []): array
    {
        [$startDate, $endDate, $prevStart, $prevEnd] = $this->parsePeriodFilters($filters);

        $accounts = $project->accounts()->with([
            'yandex_direct_campaigns.statistics' => function ($query) use ($startDate, $endDate, $prevStart, $prevEnd) {
                $query->where(function ($q) use ($startDate, $endDate, $prevStart, $prevEnd) {
                    $q->whereBetween('date', [$startDate, $endDate])
                        ->orWhereBetween('date', [$prevStart, $prevEnd]);
                });
            },
        ])->get();

        return $this->processAccountsStatistics($accounts, $startDate, $endDate, $prevStart, $prevEnd);
    }

    public function getAccountDetailedStatistics(Project $project, Account $account, array $filters = []): array
    {
        [$startDate, $endDate, $prevStart, $prevEnd] = $this->parsePeriodFilters($filters);

        $accountType = $account->account_type;

        $data = match ($accountType) {
            'yandex_direct' => $this->getYandexDirectAccountStatistics($account, $startDate, $endDate, $prevStart, $prevEnd),
             default => [],
        };

        return $data;
    }

    private function parsePeriodFilters(array $filters): array
    {
        $startDate = $filters['start_date'] ?? now()->subDay()->format('Y-m-d');
        $endDate = $filters['end_date'] ?? now()->format('Y-m-d');

        $currentStart = Carbon::parse($startDate);
        $currentEnd = Carbon::parse($endDate);

        $periodDays = $currentStart->diffInDays($currentEnd) + 1;

        $prevEnd = $currentStart->copy()->subDay();
        $prevStart = $prevEnd->copy()->subDays($periodDays - 1);

        return [
            $startDate,
            $endDate,
            $prevStart->format('Y-m-d'),
            $prevEnd->format('Y-m-d')
        ];
    }

    private function processProjectsStatistics(Collection $projects, string $startDate, string $endDate, string $prevStart, string $prevEnd): Collection
    {
        return $projects->each(function ($project) use ($startDate, $endDate, $prevStart, $prevEnd) {
            $stats = $this->calculateProjectAggregatedStats($project, $startDate, $endDate, $prevStart, $prevEnd);

            $project->yandex_direct = $this->formatDirectStats($stats['direct']);
            $project->amount = $stats['amount'];
        });
    }

    private function calculateProjectAggregatedStats(Project $project, string $startDate, string $endDate, string $prevStart, string $prevEnd): array
    {
        $directStats = ['current' => ['conversions' => 0, 'cpa' => 0], 'prev' => ['conversions' => 0, 'cpa' => 0]];
        $hasDirectAccount = false;

        $amount = 0;

        foreach ($project->accounts as $account) {
            switch ($account->account_type) {
                case 'yandex_direct':
                    $hasDirectAccount = true;
                    $this->processDirectAccountForProject($account, $startDate, $endDate, $prevStart, $prevEnd, $directStats);
                    $amount += $account->amount ?? 0;
                    break;
            }
        }

        return [
            'direct' => ['data' => $directStats, 'has_account' => $hasDirectAccount],
            'amount' => $amount
        ];
    }

    private function processDirectAccountForProject(
        Account $account,
        string $startDate, string $endDate,
        string $prevStart, string $prevEnd,
        array &$stats
    ): void
    {
        $allStats = $this->collectAccountStatistics($account, 'yandex_direct_campaigns');

        $currentStats = $allStats->filter(fn($stat) =>
            Carbon::parse($stat->date)->between($startDate, $endDate)
        );

        $prevStats = $allStats->filter(fn($stat) =>
            Carbon::parse($stat->date)->between($prevStart, $prevEnd)
        );

        $currentAggregated = $this->yandexDirectStatisticsService->aggregateStatistics(
            $currentStats->toArray(),
            ['conversions', 'cpa']
        );

        $stats['current']['conversions'] += $currentAggregated['conversions'] ?? 0;
        $stats['current']['cpa'] += $currentAggregated['cpa'] ?? 0;

        $prevAggregated = $this->yandexDirectStatisticsService->aggregateStatistics(
            $prevStats->toArray(),
            ['conversions', 'cpa']
        );

        $stats['prev']['conversions'] += $prevAggregated['conversions'] ?? 0;
        $stats['prev']['cpa'] += $prevAggregated['cpa'] ?? 0;
    }

    private function formatDirectStats(array $data): array
    {
        $current = $data['data']['current'];
        $prev = $data['data']['prev'];

        return [
            'is_empty' => !$data['has_account'], // Меняем на has_account
            'conversions' => [
                'current' => $current['conversions'],
                'dynamic' => $current['conversions'] - $prev['conversions']
            ],
            'cpa' => [
                'current' => $current['cpa'],
                'dynamic' => $current['cpa'] - $prev['cpa']
            ]
        ];
    }

    private function processAccountsStatistics(Collection $accounts, string $startDate, string $endDate, string $prevStart, string $prevEnd): array
    {
        $groupedAccounts = [
            'yandex_direct' => [],
        ];

        foreach ($accounts as $account) {
            $accountData = match ($account->account_type) {
                'yandex_direct' => $this->processDirectAccountDetailed($account, $startDate, $endDate, $prevStart, $prevEnd),
                default => null
            };

            if ($accountData) {
                $account->total = $accountData['total'];
                $groupedAccounts[$account->account_type][] = $account;
            }
        }

        return $groupedAccounts;
    }

    private function getYandexDirectAccountStatistics(Account $account, string $startDate, string $endDate, string $prevStart, string $prevEnd): array
    {
        $account->load(['yandex_direct_campaigns.statistics' => function ($query) use ($startDate, $endDate, $prevStart, $prevEnd) {
            $query->where(function ($q) use ($startDate, $endDate, $prevStart, $prevEnd) {
                $q->whereBetween('date', [$startDate, $endDate])
                    ->orWhereBetween('date', [$prevStart, $prevEnd]);
            });
        }]);

        $allStatistics = collect();
        foreach ($account->yandex_direct_campaigns as $campaign) {
            $allStatistics = $allStatistics->merge($campaign->statistics);
        }

        $groupedStatistics = $allStatistics->groupBy('date')->map(function($dayStats) {
            return [
                'date' => $dayStats->first()->date,
                'clicks' => $dayStats->sum('clicks'),
                'impressions' => $dayStats->sum('impressions'),
                'ctr' => $dayStats->avg('ctr'),
                'avg_cpc' => $dayStats->avg('avg_cpc'),
                'cost' => $dayStats->sum('cost'),
                'conversions' => $dayStats->sum('conversions'),
                'cost_per_conversion' => $dayStats->avg('cost_per_conversion'),
                'conversion_rate' => $dayStats->avg('conversion_rate'),
            ];
        })->values();

        $currentStats = $groupedStatistics->filter(fn($stat) =>
        Carbon::parse($stat['date'])->between($startDate, $endDate)
        );

        $prevStats = $groupedStatistics->filter(fn($stat) =>
        Carbon::parse($stat['date'])->between($prevStart, $prevEnd)
        );

        $prevStatsData = $this->yandexDirectStatisticsService->aggregateStatistics($prevStats->toArray());
        $currStatsData = $this->yandexDirectStatisticsService->aggregateStatistics($currentStats->toArray());

        $currStatsData['dynamic'] = [
            'clicks' => $currStatsData['clicks'] - $prevStatsData['clicks'],
            'impressions' => $currStatsData['impressions'] - $prevStatsData['impressions'],
            'ctr' => $currStatsData['ctr'] - $prevStatsData['ctr'],
            'conversions' => $currStatsData['conversions'] - $prevStatsData['conversions'],
            'cost' => $currStatsData['cost'] - $prevStatsData['cost'],
            'cpc' => $currStatsData['cpc'] - $prevStatsData['cpc'],
            'cpa' => $currStatsData['cpa'] - $prevStatsData['cpa'],
            'conversion_rate' => $currStatsData['conversion_rate'] - $prevStatsData['conversion_rate'],
        ];

        $campaignsWithStats = $this->processDirectCampaignsStats($account->yandex_direct_campaigns, $startDate, $endDate, $prevStart, $prevEnd);

        return [
            'total' => $currStatsData,
            'account_stats' => $currentStats->values()->toArray(),
            'campaigns_stats' => $campaignsWithStats
        ];
    }


    private function processDirectCampaignsStats($campaigns, string $startDate, string $endDate, string $prevStart, string $prevEnd): array
    {
        $campaignsWithStats = [];

        foreach ($campaigns as $campaign) {
            $campaignStatistics = collect($campaign->statistics);

            $campaignGroupedStats = $campaignStatistics->groupBy('date')->map(function($dayStats) {
                return [
                    'date' => $dayStats->first()->date,
                    'clicks' => $dayStats->sum('clicks'),
                    'impressions' => $dayStats->sum('impressions'),
                    'ctr' => $dayStats->avg('ctr'),
                    'avg_cpc' => $dayStats->avg('avg_cpc'),
                    'cost' => $dayStats->sum('cost'),
                    'conversions' => $dayStats->sum('conversions'),
                    'cost_per_conversion' => $dayStats->avg('cost_per_conversion'),
                    'conversion_rate' => $dayStats->avg('conversion_rate'),
                ];
            })->values();

            $currentCampaignStats = $campaignGroupedStats->filter(fn($stat) =>
                Carbon::parse($stat['date'])->between($startDate, $endDate)
            );

            $prevCampaignStats = $campaignGroupedStats->filter(fn($stat) =>
                Carbon::parse($stat['date'])->between($prevStart, $prevEnd)
            );

            $prevCampaignData = $this->yandexDirectStatisticsService->aggregateStatistics($prevCampaignStats->toArray());
            $currCampaignData = $this->yandexDirectStatisticsService->aggregateStatistics($currentCampaignStats->toArray());

            $currCampaignData['dynamic'] = [
                'clicks' => $currCampaignData['clicks'] - $prevCampaignData['clicks'],
                'impressions' => $currCampaignData['impressions'] - $prevCampaignData['impressions'],
                'ctr' => $currCampaignData['ctr'] - $prevCampaignData['ctr'],
                'conversions' => $currCampaignData['conversions'] - $prevCampaignData['conversions'],
                'cost' => $currCampaignData['cost'] - $prevCampaignData['cost'],
                'cpc' => $currCampaignData['cpc'] - $prevCampaignData['cpc'],
                'cpa' => $currCampaignData['cpa'] - $prevCampaignData['cpa'],
                'conversion_rate' => $currCampaignData['conversion_rate'] - $prevCampaignData['conversion_rate'],
            ];

            $campaignsWithStats[] = [
                'id' => $campaign->id,
                'name' => $campaign->campaign_name,
                'campaign_id' => $campaign->campaign_id,
                'status' => $campaign->status,
                'total' => $currCampaignData,
                'daily_stats' => $currentCampaignStats->values()->toArray(),
            ];
        }

        return $campaignsWithStats;
    }


    private function collectAccountStatistics(Account $account, string $relation, string $statsKey = 'statistics'): Collection
    {
        $allStatistics = collect();

        if ($account->{$relation}) {
            foreach ($account->{$relation} as $item) {
                if ($item->{$statsKey}) {
                    $allStatistics = $allStatistics->merge($item->{$statsKey});
                }
            }
        }

        return $allStatistics;
    }

    private function processDirectAccountDetailed(Account $account, string $startDate, string $endDate, string $prevStart, string $prevEnd): array
    {
        $allStatistics = collect();
        foreach ($account->yandex_direct_campaigns as $campaign) {
            $allStatistics = $allStatistics->merge($campaign->statistics);
        }

        $currentStats = $allStatistics->filter(fn($stat) =>
        Carbon::parse($stat->date)->between($startDate, $endDate)
        );

        $prevStats = $allStatistics->filter(fn($stat) =>
        Carbon::parse($stat->date)->between($prevStart, $prevEnd)
        );

        $prevStatsData = $this->yandexDirectStatisticsService->aggregateStatistics($prevStats->toArray());
        $currStatsData = $this->yandexDirectStatisticsService->aggregateStatistics($currentStats->toArray());

        $currStatsData['dynamic'] = [
            'clicks' => $currStatsData['clicks'] - $prevStatsData['clicks'],
            'impressions' => $currStatsData['impressions'] - $prevStatsData['impressions'],
            'ctr' => $currStatsData['ctr'] - $prevStatsData['ctr'],
            'conversions' => $currStatsData['conversions'] - $prevStatsData['conversions'],
            'cost' => $currStatsData['cost'] - $prevStatsData['cost'],
            'cpc' => $currStatsData['cpc'] - $prevStatsData['cpc'],
            'cpa' => $currStatsData['cpa'] - $prevStatsData['cpa'],
            'conversion_rate' => $currStatsData['conversion_rate'] - $prevStatsData['conversion_rate'],
        ];

        return ['total' => $currStatsData];
    }
}
