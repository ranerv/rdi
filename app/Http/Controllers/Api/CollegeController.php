<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCollegeRequest;
use App\Http\Requests\UpdateCollegeRequest;
use App\Http\Resources\CollegeResource;
use App\Models\College;
use Illuminate\Http\JsonResponse;

class CollegeController
{
    public function index(): JsonResponse
    {
        $colleges = College::with('departments')->paginate(15);

        return response()->json([
            'data' => CollegeResource::collection($colleges->items()),
            'pagination' => [
                'total' => $colleges->total(),
                'per_page' => $colleges->perPage(),
                'current_page' => $colleges->currentPage(),
                'last_page' => $colleges->lastPage(),
            ],
            'message' => 'Colleges retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreCollegeRequest $request): JsonResponse
    {
        $this->authorize('create', College::class);

        $college = College::create($request->validated());

        return response()->json([
            'data' => new CollegeResource($college),
            'message' => 'College created successfully',
            'status' => true,
        ], 201);
    }

    public function show(College $college): JsonResponse
    {
        return response()->json([
            'data' => new CollegeResource($college->load('departments')),
            'message' => 'College retrieved',
            'status' => true,
        ]);
    }

    public function update(UpdateCollegeRequest $request, College $college): JsonResponse
    {
        $this->authorize('update', $college);

        $college->update($request->validated());

        return response()->json([
            'data' => new CollegeResource($college),
            'message' => 'College updated successfully',
            'status' => true,
        ]);
    }

    public function destroy(College $college): JsonResponse
    {
        $this->authorize('delete', $college);

        $college->delete();

        return response()->json([
            'data' => null,
            'message' => 'College deleted successfully',
            'status' => true,
        ]);
    }
}
