<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dosage;
use Illuminate\Auth\Access\HandlesAuthorization;

class DosagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given user can add a dosage.
     *
     * @param User $user
     * @return bool
     */
    public function add(User $user): bool
    {
        // Any user can add dosages
        return true;
    }

    /**
     * Determine if the given user can view the dosage.
     *
     * @param User $user
     * @param Dosage $dosage
     * @return bool
     */
    public function view(User $user, Dosage $dosage): bool
    {
        // Only dosages of the same clinic can be viewed by the user
        return $user->clinic->id === $dosage->clinic->id;
    }

    /**
     * Determine if the given user can edit the dosage.
     *
     * @param User $user
     * @param Dosage $dosage
     * @return bool
     */
    public function edit(User $user, Dosage $dosage): bool
    {
        // Only users from the same clinic can edit the dosage
        return $user->clinic->id === $dosage->clinic->id;
    }

    /**
     * Determine if the given user can delete the dosage.
     *
     * @param User $user
     * @param Dosage $dosage
     * @return bool
     */
    public function delete(User $user, Dosage $dosage): bool
    {
        // Only the admin can delete a dosage of the same clinic
        return $user->isAdmin() && $user->clinic->id === $dosage->clinic->id;
    }
}
