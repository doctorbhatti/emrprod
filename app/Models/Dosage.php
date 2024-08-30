<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dosages'; // Table name explicitly defined

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'created_by', 'clinic_id',
    ];

    /**
     * Get the user who created the dosage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    /**
     * Get the clinic associated with the dosage.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id', 'id');
    }
}
