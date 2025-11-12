<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_time',
        'location',
        'max_attendees',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendees')
                    ->withTimestamps();
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('M j, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return date('g:i A', strtotime($this->event_time));
    }

    public function getIsUpcomingAttribute()
    {
        return $this->event_date >= now()->startOfDay();
    }
}