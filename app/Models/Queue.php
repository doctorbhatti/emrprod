<?php

namespace App\Models;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'queues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'clinic_id', 'created_by', 'active'
    ];

    /**
     * User's clinic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id', 'id');
    }

    /**
     * Get the patients of this queue
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function patients()
    {
        return $this->belongsToMany('App\Models\Patient', 'queue_patients', 'queue_id', 'patient_id')->withTimestamps();
    }

    /**
     * Get the user who created the queue
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    /**
     * Get the current queue of the clinic
     *
     * @return \App\Models\Queue|null
     */
    public static function getCurrentQueue()
    {
        $clinic = Clinic::getCurrentClinic();
        if (!$clinic) {
            return null;
        }
        $queue = $clinic->queues()->orderBy('id', 'desc')->first();
        if (empty($queue) || !$queue->active) {
            return null;
        }
        return $queue;
    }
}
