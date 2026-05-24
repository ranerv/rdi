<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController
{
    public function __construct(private ProjectService $projectService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = [
            'type' => $request->query('type'),
            'status' => $request->query('status'),
            'funding_type' => $request->query('funding_type'),
            'college_id' => $request->query('college_id'),
            'search' => $request->query('search'),
            'page' => $request->query('page', 1),
            'per_page' => $request->query('per_page', 15),
        ];

        $projects = $this->projectService->getWithFilters($filters);

        return response()->json([
            'data' => ProjectResource::collection($projects->items()),
            'pagination' => [
                'total' => $projects->total(),
                'per_page' => $projects->perPage(),
                'current_page' => $projects->currentPage(),
                'last_page' => $projects->lastPage(),
            ],
            'message' => 'Projects retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $project = $this->projectService->createProject($request->validated());

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => 'Project created successfully',
            'status' => true,
        ], 201);
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return response()->json([
            'data' => new ProjectResource($project->load('department', 'leadProponent', 'members', 'budget', 'monitoringReports', 'uploadedDocuments', 'publications', 'presentations', 'ipApplications')),
            'message' => 'Project retrieved',
            'status' => true,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project = $this->projectService->updateProject($project, $request->validated());

        return response()->json([
            'data' => new ProjectResource($project),
            'message' => 'Project updated successfully',
            'status' => true,
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json([
            'data' => null,
            'message' => 'Project deleted successfully',
            'status' => true,
        ]);
    }

    public function importExcel(Request $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);

        $result = $this->projectService->importFromExcel($request->file('file'));

        return response()->json([
            'data' => $result,
            'message' => 'Projects imported successfully',
            'status' => true,
        ], 201);
    }
}
