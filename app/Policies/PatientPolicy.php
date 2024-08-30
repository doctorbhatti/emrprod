<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy {
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
     * Can the user get a patient's information?
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function view(User $user, Patient $patient) {
        // Only users from the same clinic can view patient information
        return $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Determine who can add patients.
     * @param User $user
     * @return bool
     */
    public function add(User $user) {
        // Users who are not deactivated can add patients
        return !$user->deactivated();
    }

    /**
     * Determine who can edit patient details.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function edit(User $user, Patient $patient) {
        // Only users from the same clinic can edit patient details
        return $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Only an admin can delete a patient.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function delete(User $user, Patient $patient) {
        // Only admins can delete patients, and they must be from the same clinic
        return $user->isAdmin() && $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Permissions to issue an ID.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function issueID(User $user, Patient $patient) {
        // Only users from the same clinic can issue an ID
        return $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Determine who can issue medical records.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function issueMedical(User $user, Patient $patient) {
        // Only doctors can issue medical records, and they must be from the same clinic
        return $user->isDoctor() && $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Determine who can prescribe medicine for a given patient.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function prescribeMedicine(User $user, Patient $patient) {
        // Nurses cannot prescribe medicine, but others from the same clinic can
        return !$user->isNurse() && $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Determine who can issue medicine to a patient.
     * By default, anyone can issue medicine to a patient.
     * @param User $user
     * @return bool
     */
    public function issueMedicine(User $user) {
        // Anyone can issue medicine to a patient
        return true;
    }

    /**
     * Determine who can view the prescriptions of a given patient.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function viewPrescriptions(User $user, Patient $patient) {
        // Only users from the same clinic can view patient prescriptions
        return $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Determine who can view the medical records of a given patient.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function viewMedicalRecords(User $user, Patient $patient) {
        // Nurses cannot view medical records, but others from the same clinic can
        return !$user->isNurse() && $user->clinic->id === $patient->clinic->id;
    }

    /**
     * Determine who can add patients to the queue.
     * @param User $user
     * @param Patient $patient
     * @return bool
     */
    public function addToQueue(User $user, Patient $patient) {
        $queue = Queue::getCurrentQueue();
        if (!empty($queue) && $queue->patients()->wherePivot('completed', false)->find($patient->id) != null) {
            // Do not allow if the patient is already in an incomplete queue
            return false;
        }
        // Allow adding to the queue if no active queue with the patient
        return true;
    }
}
