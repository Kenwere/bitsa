<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'bio',
        'skills',
        'avatar',
        'is_active' // Add this
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'skills' => 'array',
        'is_active' => 'boolean', // Add this
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function meetingParticipants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_attendees')->withTimestamps();
    }
}