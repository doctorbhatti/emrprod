<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DosagePolicy {
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Any user can add dosages.
     *
     * @param User $user
     * @return bool
     */
    public function add(User $user) {
        // Allow all users to add dosages
        return true;
    }

    /**
     * Only a dosage of the same clinic can be viewed by a user.
     *
     * @param User $user
     * @param $dosage
     * @return bool
     */
    public function view(User $user, $dosage) {
        // Check if the dosage belongs to the same clinic as the user
        return $user->clinic->id === $dosage->clinic->id;
    }

    /**
     * Define who can edit the dosage details.
     *
     * @param User $user
     * @param $dosage
     * @return bool
     */
    public function edit(User $user, $dosage) {
        // Check if the dosage belongs to the same clinic as the user
        return $user->clinic->id === $dosage->clinic->id;
    }

    /**
     * Only the admin can delete a dosage.
     *
     * @param User $user
     * @param $dosage
     * @return bool
     */
    public function delete(User $user, $dosage) {
        // Check if the user is an admin and if the dosage belongs to the same clinic
        return $user->isAdmin() && $user->clinic->id === $dosage->clinic->id;
    }
}
