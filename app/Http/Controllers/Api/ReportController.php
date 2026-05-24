<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController
{
    public function __construct(private ReportService $reportService) {}

    public function exportPdf(Request $request): Response
    {
        $request->validate([
            'project_ids' => 'nullable|array',
            'status' => 'nullable|string',
        ]);

        $query = Project::query();

        if ($request->project_ids) {
            $query->whereIn('id', $request->project_ids);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $projects = $query->with('department', 'leadProponent', 'budget', 'publications')->get();

        return $this->reportService->generateProjectsPdf($projects);
    }

    public function exportExcel(Request $request): Response
    {
        $request->validate([
            'project_ids' => 'nullable|array',
            'status' => 'nullable|string',
        ]);

        $query = Project::query();

        if ($request->project_ids) {
            $query->whereIn('id', $request->project_ids);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $projects = $query->with('department', 'leadProponent', 'budget', 'publications')->get();

        return $this->reportService->generateProjectsExcel($projects);
    }
}
