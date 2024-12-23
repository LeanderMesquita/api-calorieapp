<?php

namespace App\Policies;

use App\Models\Entrie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EntriePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Entrie $entrie): bool
    {
        return $user->id === $entrie->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Entrie $entrie): bool
    {
        return $user->id === $entrie->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Entrie $entrie): bool
    {
        return $user->id === $entrie->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Entrie $entrie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Entrie $entrie): bool
    {
        return false;
    }
}
