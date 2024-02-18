<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Classes;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassesPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->can('list_classes');
    }

    public function create(User $user)
    {
        return $user->can('create_classes');
    }

    public function deleteMany(User $user)
    {
        return $user->can('deleteMany_classes');
    }

    public function delete(User $user)
    {
        return $user->can('delete_classes');
    }

    public function update(User $user)
    {
        return $user->can('update_classes');
    }

    public function showNavigation(User $user)
    {
        return $user->can('showNavigation_classes');
    }
}
