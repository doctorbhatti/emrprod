<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'drugs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'manufacturer', 'quantity', 'drug_type_id', 'created_by', 'clinic_id',
    ];

    /**
     * Get the clinic associated with the drug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id', 'id');
    }

    /**
     * Get the quantity type of the drug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quantityType()
    {
        return $this->belongsTo('App\Models\DrugType', 'drug_type_id', 'id');
    }

    /**
     * Get the quantity type as a string.
     *
     * @return string
     */
    public function getQuantityType()
    {
        return $this->quantityType->drug_type;
    }

    /**
     * Get the user who created the drug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    /**
     * Get the stocks of this drug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany('App\Models\Stock', 'drug_id', 'id');
    }

    /**
     * Get the most recent 'n' stocks of a drug.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStocks($limit = 5)
    {
        return $this->stocks()->orderBy('id', 'desc')->take($limit)->get();
    }
}
