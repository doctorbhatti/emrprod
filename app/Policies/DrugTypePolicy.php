<?php

namespace App\Policies;

use App\Models\Clinic;
use App\Models\DrugType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DrugTypePolicy
{
    use HandlesAuthorization;

    /**
     * DrugTypePolicy constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Anyone can add drug types.
     *
     * @param User $user
     * @return bool
     */
    public function add(User $user)
    {
        // Allow any user to add drug types
        return true;
    }

    /**
     * Only admin can delete drug types.
     *
     * @param User $user
     * @param DrugType $drugType
     * @return bool
     */
    public function delete(User $user, DrugType $drugType)
    {
        // Only admins can delete drug types, and they must belong to the same clinic as the drug type
        return $user->isAdmin() && $user->clinic->id === $drugType->clinic->id;
    }
}
