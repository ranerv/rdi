<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreMonitoringReportRequest;
use App\Http\Resources\MonitoringReportResource;
use App\Models\MonitoringReport;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonitoringReportController
{
    public function index(Request $request): JsonResponse
    {
        $query = MonitoringReport::query();

        if ($request->query('project_id')) {
            $query->where('project_id', $request->query('project_id'));
        }

        if ($request->query('quarter')) {
            $query->where('quarter', $request->query('quarter'));
        }

        if ($request->query('year')) {
            $query->where('year', $request->query('year'));
        }

        $reports = $query->with('project', 'officer')->paginate(15);

        return response()->json([
            'data' => MonitoringReportResource::collection($reports->items()),
            'pagination' => [
                'total' => $reports->total(),
                'per_page' => $reports->perPage(),
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
            ],
            'message' => 'Monitoring reports retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreMonitoringReportRequest $request): JsonResponse
    {
        $this->authorize('create', MonitoringReport::class);

        $report = MonitoringReport::create($request->validated() + [
            'officer_id' => auth()->id(),
        ]);

        return response()->json([
            'data' => new MonitoringReportResource($report),
            'message' => 'Monitoring report created successfully',
            'status' => true,
        ], 201);
    }

    public function show(MonitoringReport $monitoringReport): JsonResponse
    {
        $this->authorize('view', $monitoringReport);

        return response()->json([
            'data' => new MonitoringReportResource($monitoringReport->load('project', 'officer')),
            'message' => 'Monitoring report retrieved',
            'status' => true,
        ]);
    }

    public function update(StoreMonitoringReportRequest $request, MonitoringReport $monitoringReport): JsonResponse
    {
        $this->authorize('update', $monitoringReport);

        $monitoringReport->update($request->validated());

        return response()->json([
            'data' => new MonitoringReportResource($monitoringReport),
            'message' => 'Monitoring report updated successfully',
            'status' => true,
        ]);
    }

    public function destroy(MonitoringReport $monitoringReport): JsonResponse
    {
        $this->authorize('delete', $monitoringReport);

        $monitoringReport->delete();

        return response()->json([
            'data' => null,
            'message' => 'Monitoring report deleted successfully',
            'status' => true,
        ]);
    }

    public function submit(MonitoringReport $monitoringReport): JsonResponse
    {
        $this->authorize('update', $monitoringReport);

        $monitoringReport->update(['status' => 'submitted']);

        return response()->json([
            'data' => new MonitoringReportResource($monitoringReport),
            'message' => 'Monitoring report submitted successfully',
            'status' => true,
        ]);
    }
}
