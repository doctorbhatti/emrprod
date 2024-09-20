<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosageFrequency extends Model
{
    protected $table = 'frequencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'created_by',
        'clinic_id',
    ];

    /**
     * Get the user who created the dosage frequency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', ownerKey: 'id');
    }

    /**
     * Get the clinic of the dosage frequency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Clinic::class, 'clinic_id', 'id');
    }
}
