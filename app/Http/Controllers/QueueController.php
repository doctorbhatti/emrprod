<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QueueController extends Controller
{
    /**
     * View the page consisting of the queue.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewQueue()
    {
        $queue = Queue::getCurrentQueue();
        return view('queue.queue', ['queue' => $queue]);
    }

    /**
     * Adds a patient to the queue.
     *
     * @param int $patientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToQueue($patientId)
    {
        try {
            $patient = Patient::findOrFail($patientId);
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Patient not found.');
        }

        $queue = Queue::getCurrentQueue();

        if (is_null($queue)) {
            return back()->with('error', 'Please start a new queue in order to add patients.');
        }

        // Check if the user has permissions to add patients to queues
        $this->authorize('addToQueue', $patient);

        // Check if the user can add patients to the current queue
        $this->authorize('addPatient', $queue);

        if ($queue->patients()->where('patients.id', $patientId)->exists()) {
            return back()->with('error', 'Patient is already in the queue.');
        }

        $queue->patients()->attach($patient, ['inProgress' => false]);

        return redirect()->route('queue')->with('success', 'Patient successfully added to the queue!');
    }

    /**
     * Creates a new Queue.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createQueue()
    {
        $this->authorize('create', Queue::class);

        DB::beginTransaction();
        try {
            $currentQueue = Queue::getCurrentQueue();

            if ($currentQueue) {
                $currentQueue->active = false;
                $currentQueue->save();
            }

            $queue = new Queue();
            $queue->date = now()->format('Y-m-d');
            $queue->creator()->associate(User::getCurrentUser());
            $queue->clinic()->associate(Clinic::getCurrentClinic());
            $queue->save();

            DB::commit();

            return back()->with('success', 'New Queue created!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Queue creation failed: ' . $e->getMessage());
            return back()->with('error', 'Could not create a new Queue.');
        }
    }

    /**
     * Close the current Queue.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeQueue()
    {
        $queue = Queue::getCurrentQueue();

        if (is_null($queue)) {
            return back()->with('error', 'No active queue found to close.');
        }

        $this->authorize('close', $queue);

        DB::beginTransaction();
        try {
            $queue->active = false;
            $queue->save();

            DB::commit();

            return back()->with('success', 'Queue closed!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Queue closure failed: ' . $e->getMessage());
            return back()->with('error', 'Could not close the current queue.');
        }
    }
}
