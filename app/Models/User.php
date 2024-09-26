<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'username', 'clinic_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User's clinic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo('App\Models\Clinic', 'clinic_id', 'id');
    }

    /**
     * User's role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Returns if this user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role && $this->role->role === 'Admin';
    }

    /**
     * Returns if this user is a doctor
     *
     * @return bool
     */
    public function isDoctor()
    {
        return $this->role && $this->role->role === 'Doctor';
    }

    /**
     * Returns if this user is a nurse
     *
     * @return bool
     */
    public function isNurse()
    {
        return $this->role && $this->role->role === 'Nurse';
    }

    /**
     * Check if the user account is deactivated.
     *
     * @return bool
     */
    public function deactivated()
    {
        return !$this->active;
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->clinic->email; // Assuming a relationship exists between User and Clinic
    }

    /**
     * Get the currently signed in user
     *
     * @return mixed
     */
    public static function getCurrentUser()
    {
        return Auth::user();
    }

    /**
     * Get the remember token for the user.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the remember token for the user.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the name of the remember token column.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
