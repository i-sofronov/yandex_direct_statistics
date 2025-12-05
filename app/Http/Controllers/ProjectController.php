<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Project;
use App\Models\ServiceType;
use App\Services\Auth\YandexAuthService;
use App\Services\StatisticsService;
use App\Services\YandexDirectStatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function __construct(
        private StatisticsService $statisticsService,
        private YandexAuthService $authService
    ) {}

    public function index(Request $request)
    {
        $filters = [
            'start_date' => $request->input('start_date', now()->subDays(1)->format('Y-m-d')),
            'end_date' => $request->input('end_date', now()->format('Y-m-d'))
        ];

        $projects = $this->statisticsService->getProjectsStatistics($filters);

        return Inertia::render('projects/index', [
            'initialProjects' => $projects,
            'filters' => $filters
        ]);
    }
}
