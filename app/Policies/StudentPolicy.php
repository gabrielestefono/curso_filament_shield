<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function list(User $user)
    {
        return $user->can('list_student');
    }

    public function update(User $user)
    {
        return $user->can('update_student');
    }

    public function delete(User $user)
    {
        return $user->can('delete_student');
    }

    public function deleteMany(User $user)
    {
        return $user->can('deleteMany_student');
    }
    public function export(User $user)
    {
        return $user->can('export_student');
    }

    public function filter(User $user)
    {
        return $user->can('filter_student');
    }

    public function create(User $user)
    {
        return $user->can('create_student');
    }

    public function showNavigation(User $user)
    {
        return $user->can('showNavigation_student');
    }
}
