<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id', // Add this
        'title',
        'description',
        'scheduled_time',
        'type',
        'meeting_id',
        'is_active',
        'participants_count',
        'created_by_type' // 'user' or 'admin'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function creator()
    {
        return $this->created_by_type === 'admin' ? $this->admin() : $this->user();
    }

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function activeParticipants()
    {
        return $this->participants()->whereNull('left_at');
    }

    // Generate unique meeting ID
    public static function generateMeetingId()
    {
        return 'meet_' . uniqid() . '_' . time();
    }

    // Check if meeting is live (active and scheduled time is now or past)
    public function getIsLiveAttribute()
    {
        return $this->is_active && $this->scheduled_time->lte(now());
    }

    // Get creator name
    public function getCreatorNameAttribute()
    {
        return $this->created_by_type === 'admin' 
            ? ($this->admin ? $this->admin->name : 'Admin')
            : ($this->user ? $this->user->name : 'User');
    }
}