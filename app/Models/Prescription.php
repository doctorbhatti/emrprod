<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaints', 'investigations', 'diagnosis', 'remarks', 'issued_at', 'issued', 'created_by', 'patient_id'
    ];

    /**
     * Get the user who created the prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    /**
     * Get the patient of this prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo('App\Models\Patient', 'patient_id', 'id');
    }

    /**
     * Get the drugs associated with this prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptionDrugs()
    {
        return $this->hasMany('App\Models\PrescriptionDrug', 'prescription_id', 'id');
    }

    /**
     * Get the pharmacy drugs associated with this prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptionPharmacyDrugs()
    {
        return $this->hasMany('App\Models\PrescriptionPharmacyDrug', 'prescription_id', 'id');
    }

    /**
     * Get the payment associated with this prescription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne('App\Models\Payment', 'prescription_id', 'id');
    }

    /**
     * Determine if the prescription has been issued.
     *
     * @return bool
     */
    public function hasIssued()
    {
        return $this->issued == 1;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'issued_at' => 'datetime',
        'issued' => 'boolean',
    ];
}
