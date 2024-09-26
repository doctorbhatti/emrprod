<?php

namespace App\Models;

use App\Models\Dosage;
use App\Models\DosageFrequency;
use App\Models\DosagePeriod;
use App\Models\Drug;
use App\Models\DrugType;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Clinic extends Authenticatable
{

    use Notifiable;
    protected $table = 'clinics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'address', 'phone', 'timezone', 'country', 'currency'
    ];

    /**
     * Users of the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'clinic_id', 'id');
    }

    /**
     * Get the patients of the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the prescriptions belonging to the patients of this clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function prescriptions()
    {
        return $this->hasManyThrough(Prescription::class, Patient::class, 'clinic_id', 'patient_id', 'id');
    }

    /**
     * Drugs of the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function drugs()
    {
        return $this->hasMany(Drug::class, 'clinic_id', 'id');
    }

    /**
     * Drug types of the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quantityTypes()
    {
        return $this->hasMany(DrugType::class, 'clinic_id', 'id');
    }

    /**
     * Queues of the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function queues()
    {
        return $this->hasMany(Queue::class, 'clinic_id', 'id');
    }

    /**
     * Dosages related information of the clinic.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dosages()
    {
        return $this->hasMany(Dosage::class, 'clinic_id', 'id');
    }

    /**
     * Get dosage frequencies related to the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dosageFrequencies()
    {
        return $this->hasMany(DosageFrequency::class, 'clinic_id', 'id');
    }

    /**
     * Get dosage periods related to the clinic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dosagePeriods()
    {
        return $this->hasMany(DosagePeriod::class, 'clinic_id', 'id');
    }

    /**
     * Get the currently logged in user's clinic
     * 
     * @return mixed
     */
    public static function getCurrentClinic()
    {
        $user = Auth::user();

        return $user ? $user->clinic : null;
    }
}
