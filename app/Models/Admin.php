<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // Specifies the table associated with the Admin model
    protected $table = 'admins';

    // Define fillable attributes to protect against mass-assignment vulnerabilities
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Define hidden attributes to prevent them from being exposed in arrays and JSON representations
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Optionally, define the guard name if you have a custom guard for admins
    protected $guard = 'admin';

    // Optionally, specify the date format or other attributes if they differ from the default settings
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // You can add relationships here if needed, for example:
    // public function roles() {
    //     return $this->belongsToMany(Role::class);
    // }
}
