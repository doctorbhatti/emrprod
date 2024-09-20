<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DosagePeriod;

class DosagePeriodPolicy
{
    /**
     * Determine whether the user can view any dosage periods.
     */
    public function viewAny(User $user)
    {
        // Allow users to view periods if they belong to the same clinic
        return $user->clinic_id !== null;
    }

    /**
     * Determine whether the user can view the dosage period.
     */
    public function view(User $user, DosagePeriod $period)
    {
        // Allow viewing if the user belongs to the same clinic as the period
        return $user->clinic_id === $period->clinic_id;
    }

    /**
     * Determine whether the user can create dosage periods.
     */
    public function create(User $user)
    {
        // Allow creating periods for any authenticated user
        return $user->clinic_id !== null;
    }

    /**
     * Determine whether the user can update the dosage period.
     */
    public function update(User $user, DosagePeriod $period)
    {
        // Allow editing if the user belongs to the same clinic
        return $user->clinic_id === $period->clinic_id;
    }

    /**
     * Determine whether the user can delete the dosage period.
     */
    public function delete(User $user, DosagePeriod $period)
    {
        // Allow deletion if the user is an admin and belongs to the same clinic
        return $user->isAdmin() && $user->clinic_id === $period->clinic_id;
    }
}
