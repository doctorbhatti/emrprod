<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class QueuePolicy
{
    use HandlesAuthorization;

    /**
     * QueuePolicy constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine who can create a new queue.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        // Users who are not nurses and when there is no current queue
        return !$user->isNurse() && Queue::getCurrentQueue() == null;
    }

    /**
     * Check whether a user can add patients to a particular queue.
     *
     * @param User $user
     * @param Queue $queue
     * @return bool
     */
    public function addPatient(User $user, Queue $queue)
    {
        // Check if the queue is active and if the user belongs to the same clinic as the queue
        return !empty($queue) && $queue->active && $user->clinic->id === $queue->clinic->id;
    }

    /**
     * Determine who can update the queue.
     *
     * @param User $user
     * @param Queue $queue
     * @param Patient $patient
     * @return bool
     */
    public function update(User $user, Queue $queue, Patient $patient)
    {
        // Check if both the queue and patient belong to the same clinic as the user
        return $queue->clinic->id === $patient->clinic->id && $patient->clinic->id === $user->clinic->id;
    }

    /**
     * Check whether a user can close a queue.
     *
     * @param User $user
     * @param Queue $queue
     * @return bool
     */
    public function close(User $user, Queue $queue)
    {
        // Users who are not nurses, and when the queue is active and belongs to the same clinic as the user
        return !empty($queue) && !$user->isNurse()
            && $queue->active && $user->clinic->id === $queue->clinic->id;
    }
}
