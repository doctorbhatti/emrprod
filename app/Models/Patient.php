<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'dob', 'address', 'phone', 'gender','created_by', 'clinic_id', 'remarks'
    ];

    /**
     * Get the clinic associated with the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic');
    }

    /**
     * Get the user who created the patient record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    /**
     * Get the prescriptions associated with the patient.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptions()
    {
        return $this->hasMany('App\Models\Prescription', 'patient_id', 'id');
    }

    /**
     * Get the queues the patient is associated with.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function queues()
    {
        return $this->belongsToMany('App\Models\Queue', 'queue_patients', 'patient_id', 'queue_id')
            ->withTimestamps();
    }
}
