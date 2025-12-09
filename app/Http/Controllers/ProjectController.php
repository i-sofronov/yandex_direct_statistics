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
//        dd($projects);
        return Inertia::render('projects/index', [
            'initialProjects' => $projects,
            'filters' => $filters
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
           'name' => 'string|required'
        ]);


        Project::create([
           'name' => $validated['name'],
           'user_id' => 1
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Проект успешно создан!');
    }

    public function show(string $id)
    {

    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $project = Project::findOrFail($id);

        $project->name = $validated['name'];

        $project->save();

        return redirect()->route('projects.index')
            ->with('success', 'Проект успешно сохранен!');
    }

    public function destroy(string $id)
    {
        $project = Project::find($id);
        $project->delete();

        return back()->with('success', 'Проект успешно удален!');
    }

    public function connect(Project $project, Request $request){
        $validated = $request->validate([
            'service_type' => 'required|in:' . join(',', ServiceType::all()),
            'callplace' => 'nullable',
        ]);

        $serviceType = ServiceType::from($validated['service_type']);

        $state = Str::random(40);

        Cache::put("oauth:{$state}", [
            'project_id' => $project->id,
            'service_type' => $serviceType->value,
            'callplace' => $validated['callplace'] ?? 'projects',
        ], now()->addMinutes(10));

        $authUrl = $this->authService->getAuthUrl($serviceType, $state);

        return redirect()->away($authUrl);
    }
}
