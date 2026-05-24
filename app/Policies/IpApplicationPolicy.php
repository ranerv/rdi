<?php

namespace App\Policies;

use App\Models\IpApplication;
use App\Models\User;

class IpApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function view(User $user, IpApplication $application): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['proponent', 'rdi-staff', 'super-admin']);
    }

    public function update(User $user, IpApplication $application): bool
    {
        return $user->id === $application->user_id || $user->hasAnyRole(['super-admin', 'rdi-staff']);
    }

    public function delete(User $user, IpApplication $application): bool
    {
        return $user->hasRole('super-admin');
    }

    public function updateStatus(User $user, IpApplication $application): bool
    {
        return $user->hasAnyRole(['ipophl-staff', 'super-admin']);
    }
}
