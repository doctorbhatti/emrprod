<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DosageFrequency;

class DosageFrequencyPolicy
{
    /**
     * Determine whether the user can view any dosage frequencies.
     */
    public function viewAny(User $user)
    {
        // Allow users to view frequencies if they belong to the same clinic
        return $user->clinic_id !== null;
    }

    /**
     * Determine whether the user can view the dosage frequency.
     */
    public function view(User $user, DosageFrequency $frequency)
    {
        // Allow viewing if the user belongs to the same clinic as the frequency
        return $user->clinic_id === $frequency->clinic_id;
    }

    /**
     * Determine whether the user can create dosage frequencies.
     */
    public function create(User $user)
    {
        // Allow creating frequencies for any authenticated user
        return $user->clinic_id !== null;
    }

    /**
     * Determine whether the user can update the dosage frequency.
     */
    public function update(User $user, DosageFrequency $frequency)
    {
        // Allow editing if the user belongs to the same clinic
        return $user->clinic_id === $frequency->clinic_id;
    }

    /**
     * Determine whether the user can delete the dosage frequency.
     */
    public function delete(User $user, DosageFrequency $dosageFrequency)
    {
        // Check if the user is an admin or belongs to the same clinic
        return $user->isAdmin() || $user->clinic_id === $dosageFrequency->clinic_id;
    }
}
