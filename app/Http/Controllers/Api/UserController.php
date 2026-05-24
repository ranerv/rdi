<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        if ($request->query('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->query('role')));
        }

        if ($request->query('search')) {
            $query->where('name', 'like', '%' . $request->query('search') . '%')
                  ->orWhere('email', 'like', '%' . $request->query('search') . '%');
        }

        $users = $query->paginate(15);

        return response()->json([
            'data' => UserResource::collection($users->items()),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
            'message' => 'Users retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());

        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User created successfully',
            'status' => true,
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User retrieved',
            'status' => true,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        if ($request->roles) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User updated successfully',
            'status' => true,
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'data' => null,
            'message' => 'User deleted successfully',
            'status' => true,
        ]);
    }
}
