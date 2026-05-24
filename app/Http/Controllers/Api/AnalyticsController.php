<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController
{
    public function __construct(private AnalyticsService $analyticsService) {}

    public function overview(): JsonResponse
    {
        $data = $this->analyticsService->overview();

        return response()->json([
            'data' => $data,
            'message' => 'Analytics overview retrieved',
            'status' => true,
        ]);
    }

    public function budget(): JsonResponse
    {
        $data = $this->analyticsService->budgetUtilization();

        return response()->json([
            'data' => $data,
            'message' => 'Budget analytics retrieved',
            'status' => true,
        ]);
    }

    public function faculty(): JsonResponse
    {
        $data = $this->analyticsService->byCollege();

        return response()->json([
            'data' => $data,
            'message' => 'Faculty involvement analytics retrieved',
            'status' => true,
        ]);
    }

    public function quarterly(): JsonResponse
    {
        $data = $this->analyticsService->quarterlyProgress();

        return response()->json([
            'data' => $data,
            'message' => 'Quarterly progress analytics retrieved',
            'status' => true,
        ]);
    }

    public function map(): JsonResponse
    {
        $data = $this->analyticsService->mapData();

        return response()->json([
            'data' => $data,
            'message' => 'Map data retrieved',
            'status' => true,
        ]);
    }
}
