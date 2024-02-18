<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Section;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectionPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->can('list_section');
    }

    public function create(User $user)
    {
        return $user->can('create_section');
    }

    public function deleteMany(User $user)
    {
        return $user->can('deleteMany_section');
    }

    public function delete(User $user)
    {
        return $user->can('delete_section');
    }

    public function update(User $user)
    {
        return $user->can('update_section');
    }

    public function showNavigation(User $user)
    {
        return $user->can('showNavigation_section');
    }
}
