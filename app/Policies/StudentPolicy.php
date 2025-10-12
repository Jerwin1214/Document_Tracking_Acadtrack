<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name ?? '', ['Admin', 'Teacher', 'Student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        return in_array($user->role->name ?? '', ['Admin', 'Teacher', 'Student']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->name ?? '', ['Admin', 'Teacher']);
    }

    /**
     * Determine whether the user can update the model.
     * For the settings page, the Student instance may not be passed.
     */
    public function update(User $user, ?Student $student = null): bool
    {
        return in_array($user->role->name ?? '', ['Admin', 'Teacher', 'Student']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        return ($user->role->name ?? '') === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        return ($user->role->name ?? '') === 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return ($user->role->name ?? '') === 'Admin';
    }
}
