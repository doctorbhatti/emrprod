<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrescriptionPolicy {
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
     * Determine who can issue a prescription to the users.
     *
     * @param User $user
     * @param Prescription $prescription
     * @return bool
     */
    public function issuePrescription(User $user, Prescription $prescription) {
        // Check if the user and the prescription's patient belong to the same clinic
        return $user->clinic->id === $prescription->patient->clinic->id;
    }

    /**
     * Determine who can delete a prescription. By default, only the admin can delete prescriptions.
     *
     * @param User $user
     * @param Prescription $prescription
     * @return bool
     */
    public function deletePrescription(User $user, Prescription $prescription) {
        // Only admins from the same clinic as the patient can delete the prescription
        return $user->isAdmin() && $user->clinic->id === $prescription->patient->clinic->id;
    }

    /**
     * Determines who can print prescriptions. Checks if the current user and the patient are from the same clinic,
     * and if the prescription's patient matches the given patient.
     *
     * @param User $user
     * @param Prescription $prescription
     * @param Patient $patient
     * @return bool
     */
    public function printPrescription(User $user, Prescription $prescription, Patient $patient) {
        // Check if the user and the patient are from the same clinic
        // and if the prescription is for the provided patient
        return $user->clinic->id === $patient->clinic->id && $prescription->patient->id === $patient->id;
    }
}
