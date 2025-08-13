<?php

namespace App\Policies;

use App\Models\User;

/**
 * Class UserPolicy
 *
 * Authorization rules for the User resource.
 * Policy rules:
 *  - Admin can list, view, create, update, and delete any user.
 *  - User can list and view only their own profile.
 *
 * @author  Leon. M. Saia <leonmsaia@gmail.com>
 * @since   2025-08-12
 * @package App\Policies
 */
class UserPolicy
{
    /**
     * Determine whether the authenticated user can view the users list.
     */
    public function viewAny(User $auth): bool
    {
        return in_array($auth->rol, ['Admin', 'User'], true);
    }

    /**
     * Determine whether the authenticated user can view a specific user.
     */
    public function view(User $auth, User $model): bool
    {
        return $auth->rol === 'Admin' || $auth->id === $model->id;
    }

    /**
     * Determine whether the authenticated user can create users.
     */
    public function create(User $auth): bool
    {
        return $auth->rol === 'Admin';
    }

    /**
     * Determine whether the authenticated user can update a user.
     */
    public function update(User $auth, User $model): bool
    {
        return $auth->rol === 'Admin';
    }

    /**
     * Determine whether the authenticated user can delete a user.
     */
    public function delete(User $auth, User $model): bool
    {
        return $auth->rol === 'Admin';
    }
}
