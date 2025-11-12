<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MeetingController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $isAdmin = Auth::guard('admin')->check();
            
            Log::info('Loading meetings for: ' . ($isAdmin ? 'Admin' : 'User'));

            // Get active meetings (live now)
            $activeMeetings = Meeting::with(['user', 'admin', 'activeParticipants.user'])
                ->where('is_active', true)
                ->where('scheduled_time', '<=', now())
                ->where(function($query) use ($user, $isAdmin) {
                    if ($isAdmin) {
                        // Admins can see all meetings
                        return;
                    }
                    // Users can see public meetings or meetings they're invited to
                    $query->where('type', 'public')
                          ->orWhereHas('participants', function($q) use ($user) {
                              $q->where('user_id', $user->id);
                          });
                })
                ->orderBy('scheduled_time', 'asc')
                ->get();

            // Get scheduled meetings (future)
            $scheduledMeetings = Meeting::with(['user', 'admin', 'participants.user'])
                ->where('scheduled_time', '>', now())
                ->where(function($query) use ($user, $isAdmin) {
                    if ($isAdmin) {
                        // Admins can see all meetings
                        return;
                    }
                    // Users can see public meetings or meetings they're invited to
                    $query->where('type', 'public')
                          ->orWhereHas('participants', function($q) use ($user) {
                              $q->where('user_id', $user->id);
                          });
                })
                ->orderBy('scheduled_time', 'asc')
                ->get();

            Log::info('Active meetings: ' . $activeMeetings->count());
            Log::info('Scheduled meetings: ' . $scheduledMeetings->count());

            return response()->json([
                'success' => true,
                'active_meetings' => $activeMeetings,
                'scheduled_meetings' => $scheduledMeetings
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading meetings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load meetings'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Creating new meeting', $request->all());

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required',
                'type' => 'required|in:public,private'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Combine date and time
            $scheduledTime = $request->date . ' ' . $request->time;
            $dateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $scheduledTime);

            $meetingData = [
                'title' => $request->title,
                'description' => $request->description,
                'scheduled_time' => $dateTime,
                'type' => $request->type,
                'meeting_id' => Meeting::generateMeetingId(),
                'is_active' => true
            ];

            // Determine creator type
            if (Auth::guard('admin')->check()) {
                $meetingData['admin_id'] = Auth::guard('admin')->id();
                $meetingData['created_by_type'] = 'admin';
            } else {
                $meetingData['user_id'] = Auth::id();
                $meetingData['created_by_type'] = 'user';
            }

            $meeting = Meeting::create($meetingData);

            // Add creator as host participant
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id' => Auth::guard('admin')->check() ? null : Auth::id(),
                'admin_id' => Auth::guard('admin')->check() ? Auth::guard('admin')->id() : null,
                'is_host' => true,
                'joined_at' => now()
            ]);

            $meeting->load(['user', 'admin', 'participants.user']);

            Log::info('Meeting created successfully: ' . $meeting->id);

            return response()->json([
                'success' => true,
                'message' => 'Meeting created successfully',
                'meeting' => $meeting
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating meeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create meeting: ' . $e->getMessage()
            ], 500);
        }
    }

    


    public function join(Meeting $meeting)
    {
        try {
            $user = Auth::user();
            Log::info('User ' . $user->id . ' joining meeting ' . $meeting->id);

            // Check if user can join this meeting
            if ($meeting->type === 'private' && !$meeting->participants()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is a private meeting'
                ], 403);
            }

            // Check if meeting is active and scheduled
            if (!$meeting->is_active || $meeting->scheduled_time->gt(now())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Meeting is not active yet'
                ], 400);
            }

            // Add user as participant if not already
            $participant = MeetingParticipant::firstOrCreate([
                'meeting_id' => $meeting->id,
                'user_id' => $user->id
            ], [
                'joined_at' => now()
            ]);

            // Update joined_at if re-joining
            if ($participant->left_at) {
                $participant->update([
                    'joined_at' => now(),
                    'left_at' => null
                ]);
            }

            // Update participants count
            $meeting->update([
                'participants_count' => $meeting->activeParticipants()->count()
            ]);

            $meeting->load(['user', 'activeParticipants.user']);

            Log::info('User joined meeting successfully');

            return response()->json([
                'success' => true,
                'message' => 'Joined meeting successfully',
                'meeting' => $meeting,
                'is_host' => $participant->is_host
            ]);

        } catch (\Exception $e) {
            Log::error('Error joining meeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to join meeting'
            ], 500);
        }
    }

    public function leave(Meeting $meeting)
    {
        try {
            $user = Auth::user();
            Log::info('User ' . $user->id . ' leaving meeting ' . $meeting->id);

            $participant = MeetingParticipant::where('meeting_id', $meeting->id)
                ->where('user_id', $user->id)
                ->first();

            if ($participant) {
                $participant->update(['left_at' => now()]);
                
                // Update participants count
                $meeting->update([
                    'participants_count' => $meeting->activeParticipants()->count()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Left meeting successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error leaving meeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to leave meeting'
            ], 500);
        }
    }

    public function rsvp(Meeting $meeting)
    {
        try {
            $user = Auth::user();
            Log::info('User ' . $user->id . ' RSVPing to meeting ' . $meeting->id);

            // Check if meeting is public or user is already invited
            if ($meeting->type === 'private' && !$meeting->participants()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This is a private meeting'
                ], 403);
            }

            // Add user as participant for RSVP
            MeetingParticipant::firstOrCreate([
                'meeting_id' => $meeting->id,
                'user_id' => $user->id
            ]);

            $meeting->load(['user', 'participants.user']);

            Log::info('RSVP successful');

            return response()->json([
                'success' => true,
                'message' => 'RSVP successful',
                'meeting' => $meeting
            ]);

        } catch (\Exception $e) {
            Log::error('Error RSVPing to meeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to RSVP'
            ], 500);
        }
    }

    public function destroy(Meeting $meeting)
    {
        try {
            // Check if user is the host
            if ($meeting->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the meeting host can delete this meeting'
                ], 403);
            }

            $meeting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Meeting deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting meeting: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete meeting'
            ], 500);
        }
    }
}