<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Budget;
use App\Models\MonitoringReport;
use App\Models\College;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function overview(): array
    {
        $totalProjects = Project::count();
        $researchCount = Project::where('type', 'research')->count();
        $extensionCount = Project::where('type', 'extension')->count();
        $ongoingCount = Project::where('status', 'ongoing')->count();
        $completedCount = Project::where('status', 'completed')->count();
        $totalBudget = Budget::sum('total_amount');
        $totalExpended = Budget::with('expenditures')->get()
            ->sum(fn($b) => $b->expenditures->sum('amount'));

        return [
            'total_projects' => $totalProjects,
            'research_count' => $researchCount,
            'extension_count' => $extensionCount,
            'ongoing_count' => $ongoingCount,
            'completed_count' => $completedCount,
            'completion_rate' => $totalProjects > 0 ? round(($completedCount / $totalProjects) * 100, 2) : 0,
            'total_budget' => $totalBudget,
            'total_expenditures' => $totalExpended,
            'budget_utilization_percent' => $totalBudget > 0 ? round(($totalExpended / $totalBudget) * 100, 2) : 0,
        ];
    }

    public function budgetUtilization(): Collection
    {
        return Project::with('budget')
            ->get()
            ->map(fn($project) => [
                'project_title' => $project->title,
                'project_id' => $project->id,
                'total_budget' => $project->approved_budget,
                'spent' => $project->budget?->expenditures?->sum('amount') ?? 0,
                'remaining' => $project->budget?->remaining_budget ?? $project->approved_budget,
            ]);
    }

    public function byCollege(): Collection
    {
        return College::withCount('departments')
            ->get()
            ->map(fn($college) => [
                'college_name' => $college->name,
                'college_id' => $college->id,
                'project_count' => Project::whereHas('department', 
                    fn($q) => $q->where('college_id', $college->id))->count(),
            ]);
    }

    public function quarterlyProgress(): Collection
    {
        return MonitoringReport::selectRaw('quarter, year, COUNT(*) as count')
            ->groupBy('quarter', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('quarter', 'desc')
            ->get()
            ->map(fn($report) => [
                'quarter' => $report->quarter,
                'year' => $report->year,
                'submissions' => $report->count,
            ]);
    }

    public function mapData(): Collection
    {
        return Project::where('latitude', '!=', null)
            ->where('longitude', '!=', null)
            ->get()
            ->map(fn($project) => [
                'id' => $project->id,
                'title' => $project->title,
                'type' => $project->type,
                'status' => $project->status,
                'latitude' => floatval($project->latitude),
                'longitude' => floatval($project->longitude),
                'location_description' => $project->location_description,
            ]);
    }
}
