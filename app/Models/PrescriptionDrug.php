<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDrug extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prescription_drugs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'drug_id', 'prescription_id', 'dosage_id', 'frequency_id', 'period_id', 'quantity'
    ];

    /**
     * Get the drug associated with this prescription drug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drug()
    {
        return $this->belongsTo('App\Models\Drug', 'drug_id', 'id');
    }

    /**
     * Get the prescription associated with this drug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prescription()
    {
        return $this->belongsTo('App\Models\Prescription', 'prescription_id', 'id');
    }

    /**
     * Get the dosage of the drug prescribed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dosage()
    {
        return $this->belongsTo('App\Models\Dosage', 'dosage_id', 'id');
    }

    /**
     * Get the dosage frequency of the drug prescribed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function frequency()
    {
        return $this->belongsTo('App\Models\DosageFrequency', 'frequency_id', 'id');
    }

    /**
     * Get the dosage period of the drug prescribed.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
        return $this->belongsTo('App\Models\DosagePeriod', 'period_id', 'id');
    }
}
