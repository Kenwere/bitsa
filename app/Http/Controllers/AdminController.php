<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Meeting;
use App\Models\Event;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
{
    try {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'total_meetings' => Meeting::count(),
            'active_meetings' => Meeting::where('is_active', true)->count(),
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('event_date', '>=', now())->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_posts = Post::with('user')->latest()->take(5)->get();
        $upcoming_events = Event::where('event_date', '>=', now())->orderBy('event_date')->take(5)->get();

        // Load all data needed for the entire dashboard
        $users = User::withCount(['posts'])->latest()->get();
        $posts = Post::with(['user', 'likes', 'comments'])->withCount(['likes', 'comments'])->latest()->get();
        $meetings = Meeting::with(['user', 'participants'])->withCount('participants')->latest()->get();
        $events = Event::latest()->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_users', 
            'recent_posts', 
            'upcoming_events',
            'users',
            'posts', 
            'meetings', 
            'events'
        ));

    } catch (\Exception $e) {
        \Log::error('Dashboard error: ' . $e->getMessage());
        return back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
    }
}

    // Separate methods for individual pages
    public function users()
    {
        $users = User::withCount(['posts'])->latest()->get();
        return view('admin.users', compact('users'));
    }

    public function posts()
    {
        $posts = Post::with(['user', 'likes', 'comments'])->withCount(['likes', 'comments'])->latest()->get();
        return view('admin.posts', compact('posts'));
    }

  public function meetings()
{
    $meetings = Meeting::with(['user', 'admin', 'participants'])
        ->withCount('participants')
        ->latest()
        ->get();
    
    return view('admin.meetings', compact('meetings'));
}

    public function events()
    {
        $events = Event::latest()->get();
        return view('admin.events', compact('events'));
    }

    public function suspendUser(User $user)
    {
        try {
            $user->update(['is_active' => false]);
            return back()->with('success', 'User suspended successfully.');
        } catch (\Exception $e) {
            \Log::error('Suspend user error: ' . $e->getMessage());
            return back()->with('error', 'Error suspending user.');
        }
    }

    public function activateUser(User $user)
    {
        try {
            $user->update(['is_active' => true]);
            return back()->with('success', 'User activated successfully.');
        } catch (\Exception $e) {
            \Log::error('Activate user error: ' . $e->getMessage());
            return back()->with('error', 'Error activating user.');
        }
    }

    public function deleteUser(User $user)
    {
        try {
            DB::transaction(function () use ($user) {
                // Delete user's posts, comments, likes, meeting participations
                $user->posts()->delete();
                $user->comments()->delete();
                $user->likes()->delete();
                $user->meetingParticipants()->delete();
                
                // Delete meetings created by user
                $user->meetings()->delete();
                
                // Finally delete user
                $user->delete();
            });

            return back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Delete user error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting user.');
        }
    }

    public function deletePost(Post $post)
    {
        try {
            $post->delete();
            return back()->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Delete post error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting post.');
        }
    }

    public function deleteMeeting(Meeting $meeting)
    {
        try {
            $meeting->delete();
            return back()->with('success', 'Meeting deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Delete meeting error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting meeting.');
        }
    }

    public function createEvent(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'event_date' => 'required|date|after:today',
                'event_time' => 'required',
                'location' => 'required|string|max:255',
                'max_attendees' => 'nullable|integer|min:1',
            ]);

            Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'event_date' => $request->event_date,
                'event_time' => $request->event_time,
                'location' => $request->location,
                'max_attendees' => $request->max_attendees,
                'created_by' => auth('admin')->id(),
                'is_active' => true,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Event created successfully.'
                ]);
            }

            return back()->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            \Log::error('Create event error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating event: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error creating event.');
        }
    }

    public function updateEvent(Request $request, Event $event)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'event_date' => 'required|date',
                'event_time' => 'required',
                'location' => 'required|string|max:255',
                'max_attendees' => 'nullable|integer|min:1',
            ]);

            $event->update($request->all());
            return back()->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Update event error: ' . $e->getMessage());
            return back()->with('error', 'Error updating event.');
        }
    }

    public function deleteEvent(Event $event)
    {
        try {
            $event->delete();
            return back()->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Delete event error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting event.');
        }
    }
    // Add to AdminController
public function createMeeting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required',
                'type' => 'required|in:public,private'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Combine date and time
            $scheduledTime = $request->date . ' ' . $request->time;
            $dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $scheduledTime);

            $meeting = Meeting::create([
                'admin_id' => Auth::guard('admin')->id(),
                'title' => $request->title,
                'description' => $request->description,
                'scheduled_time' => $dateTime,
                'type' => $request->type,
                'meeting_id' => Meeting::generateMeetingId(),
                'is_active' => true,
                'created_by_type' => 'admin',
                'participants_count' => 1 // Admin is automatically a participant
            ]);

            // Add admin as host participant
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'admin_id' => Auth::guard('admin')->id(),
                'user_id' => null,
                'is_host' => true,
                'joined_at' => now()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Meeting created successfully!',
                    'meeting' => $meeting
                ]);
            }

            return back()->with('success', 'Meeting created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating meeting: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating meeting: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error creating meeting.');
        }
    }
}