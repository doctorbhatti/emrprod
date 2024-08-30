<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'received_date', 'expiry_date', 'manufactured_date', 'quantity', 'remarks'
    ];

    /**
     * Get the drug of the given stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function drug()
    {
        return $this->belongsTo('App\Models\Drug', 'drug_id', 'id');
    }

    /**
     * Get the user who created the stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    // Optional: Disable timestamps if not used
    // public $timestamps = false;
}
