<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name (optional)
    protected $table = 'notifications';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'clinic_id',
        'message',
        'read_status',
        'read_at',
    ];

    /**
     * Relationship: Each notification belongs to a clinic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

     public function clinics()
    {
        return $this->belongsToMany(Clinic::class)->withPivot('read_status'); // Assuming you have a pivot table for this
    }


    /**
     * Scope to filter unread notifications.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('read_status', false);
    }

    /**
     * Mark the notification as read.
     *
     * @return bool
     */
    public function markAsRead()
    {
        $this->read_status = true;
        $this->read_at = now();
        return $this->save();
    }
}
