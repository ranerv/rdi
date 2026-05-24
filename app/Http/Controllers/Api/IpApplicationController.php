<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreIpApplicationRequest;
use App\Http\Requests\UpdateIpStatusRequest;
use App\Http\Resources\IpApplicationResource;
use App\Models\IpApplication;
use App\Services\IpApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IpApplicationController
{
    public function __construct(private IpApplicationService $ipService) {}

    public function index(Request $request): JsonResponse
    {
        $query = IpApplication::query();

        if ($request->query('project_id')) {
            $query->where('project_id', $request->query('project_id'));
        }

        if ($request->query('status')) {
            $query->where('status', $request->query('status'));
        }

        $applications = $query->with('project', 'user', 'reviewer', 'ipCertificate')->paginate(15);

        return response()->json([
            'data' => IpApplicationResource::collection($applications->items()),
            'pagination' => [
                'total' => $applications->total(),
                'per_page' => $applications->perPage(),
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
            ],
            'message' => 'IP applications retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreIpApplicationRequest $request): JsonResponse
    {
        $application = IpApplication::create($request->validated() + [
            'user_id' => auth()->id(),
            'submitted_at' => now(),
        ]);

        return response()->json([
            'data' => new IpApplicationResource($application),
            'message' => 'IP application submitted successfully',
            'status' => true,
        ], 201);
    }

    public function show(IpApplication $ipApplication): JsonResponse
    {
        return response()->json([
            'data' => new IpApplicationResource($ipApplication->load('project', 'user', 'reviewer', 'ipCertificate')),
            'message' => 'IP application retrieved',
            'status' => true,
        ]);
    }

    public function update(StoreIpApplicationRequest $request, IpApplication $ipApplication): JsonResponse
    {
        $ipApplication->update($request->validated());

        return response()->json([
            'data' => new IpApplicationResource($ipApplication),
            'message' => 'IP application updated successfully',
            'status' => true,
        ]);
    }

    public function destroy(IpApplication $ipApplication): JsonResponse
    {
        $ipApplication->delete();

        return response()->json([
            'data' => null,
            'message' => 'IP application deleted successfully',
            'status' => true,
        ]);
    }

    public function updateStatus(UpdateIpStatusRequest $request, IpApplication $ipApplication): JsonResponse
    {
        $this->authorize('updateStatus', $ipApplication);

        $this->ipService->updateStatus(
            $ipApplication,
            $request->status,
            $request->remarks ?? null
        );

        return response()->json([
            'data' => new IpApplicationResource($ipApplication->refresh()),
            'message' => 'IP application status updated successfully',
            'status' => true,
        ]);
    }
}
