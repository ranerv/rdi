<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function view(User $user, Project $project): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'rdi-staff']);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasAnyRole(['super-admin', 'rdi-staff']) || $user->id === $project->lead_proponent_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('super-admin');
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $user->hasRole('super-admin');
    }
}
