<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drug_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'drug_type', 'created_by', 'clinic_id',
    ];

    /**
     * Get the clinic associated with the drug type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id', 'id');
    }

    /**
     * Get the user who created the drug type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }
}
