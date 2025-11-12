<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('event_date', '>=', now()->startOfDay())
                      ->where('is_active', true)
                      ->orderBy('event_date')
                      ->get()
                      ->map(function ($event) {
                          return [
                              'id' => $event->id,
                              'title' => $event->title,
                              'description' => $event->description,
                              'formatted_date' => $event->formatted_date,
                              'formatted_time' => $event->formatted_time,
                              'location' => $event->location,
                              'max_attendees' => $event->max_attendees,
                              'is_upcoming' => $event->is_upcoming,
                          ];
                      });

        return response()->json($events);
    }
}