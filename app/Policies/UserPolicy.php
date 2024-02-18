<?php

namespace App\Policies;

use App\Models\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function deleteMany(User $user)
    {
        return $user->can('deleteMany_user');
    }

    public function create(User $user)
    {
        return $user->can('create_user');
    }

    public function update(User $user)
    {
        return $user->can('update_user');
    }

    public function delete(User $user)
    {
        return $user->can('delete_user');
    }

    public function list(User $user)
    {
        return $user->can('list_user');
    }

    public function showNavigation(User $user)
    {
        return $user->can('showNavigation_user');
    }
}
