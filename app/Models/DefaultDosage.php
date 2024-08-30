<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DefaultDosage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'default_dosages'; // Specify the table name if it's not the plural form of the model name

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', // Add other attributes that are mass assignable
        'value',       // Example attributes, adjust according to your database schema
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; // Set to false if your table does not have `created_at` and `updated_at` columns

    // Define any relationships here if applicable
    // For example:
    // public function dosages()
    // {
    //     return $this->hasMany(Dosage::class, 'default_dosage_id', 'id');
    // }
}
