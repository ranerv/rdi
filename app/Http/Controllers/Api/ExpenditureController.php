<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreExpenditureRequest;
use App\Http\Resources\ExpenditureResource;
use App\Models\Budget;
use App\Models\Expenditure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenditureController
{
    public function index(Budget $budget): JsonResponse
    {
        $expenditures = $budget->expenditures()->paginate(15);

        return response()->json([
            'data' => ExpenditureResource::collection($expenditures->items()),
            'pagination' => [
                'total' => $expenditures->total(),
                'per_page' => $expenditures->perPage(),
                'current_page' => $expenditures->currentPage(),
                'last_page' => $expenditures->lastPage(),
            ],
            'message' => 'Expenditures retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreExpenditureRequest $request, Budget $budget): JsonResponse
    {
        $expenditure = $budget->expenditures()->create($request->validated());

        return response()->json([
            'data' => new ExpenditureResource($expenditure),
            'message' => 'Expenditure recorded successfully',
            'status' => true,
        ], 201);
    }

    public function show(Expenditure $expenditure): JsonResponse
    {
        return response()->json([
            'data' => new ExpenditureResource($expenditure),
            'message' => 'Expenditure retrieved',
            'status' => true,
        ]);
    }

    public function destroy(Expenditure $expenditure): JsonResponse
    {
        $expenditure->delete();

        return response()->json([
            'data' => null,
            'message' => 'Expenditure deleted successfully',
            'status' => true,
        ]);
    }
}
