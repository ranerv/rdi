<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Http\Resources\BudgetResource;
use App\Models\Budget;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class BudgetController
{
    public function index(Project $project): JsonResponse
    {
        $budget = $project->budget;

        if (!$budget) {
            return response()->json([
                'data' => null,
                'message' => 'Budget not found for this project',
                'status' => false,
            ], 404);
        }

        return response()->json([
            'data' => new BudgetResource($budget->load('expenditures')),
            'message' => 'Budget retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreBudgetRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $budget = Budget::updateOrCreate(
            ['project_id' => $project->id],
            $request->validated()
        );

        return response()->json([
            'data' => new BudgetResource($budget),
            'message' => 'Budget saved successfully',
            'status' => true,
        ], 201);
    }

    public function show(Budget $budget): JsonResponse
    {
        return response()->json([
            'data' => new BudgetResource($budget->load('expenditures')),
            'message' => 'Budget retrieved',
            'status' => true,
        ]);
    }

    public function update(UpdateBudgetRequest $request, Budget $budget): JsonResponse
    {
        $this->authorize('update', $budget->project);

        $budget->update($request->validated());

        return response()->json([
            'data' => new BudgetResource($budget),
            'message' => 'Budget updated successfully',
            'status' => true,
        ]);
    }

    public function destroy(Budget $budget): JsonResponse
    {
        $this->authorize('delete', $budget->project);

        $budget->delete();

        return response()->json([
            'data' => null,
            'message' => 'Budget deleted successfully',
            'status' => true,
        ]);
    }
}
