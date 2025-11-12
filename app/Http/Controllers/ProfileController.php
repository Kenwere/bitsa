<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'bio' => $user->bio ?? '',
                    'skills' => $user->skills ?? [],
                    'posts_count' => $user->posts()->count(),
                    'created_at' => $user->created_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'bio' => 'nullable|string|max:500',
                'skills' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Convert skills string to array if provided
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'bio' => $request->bio,
            ];

            if ($request->has('skills') && !empty($request->skills)) {
                $skillsArray = array_map('trim', explode(',', $request->skills));
                $updateData['skills'] = $skillsArray;
            }

            $user->update($updateData);

            Log::info('Profile updated for user: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'bio' => $user->bio,
                    'skills' => $user->skills,
                    'posts_count' => $user->posts()->count(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile'
            ], 500);
        }
    }

    public function stats()
    {
        try {
            $user = Auth::user();
            
            return response()->json([
                'success' => true,
                'stats' => [
                    'posts_count' => $user->posts()->count(),
                    'following_count' => 0, // You can implement this later
                    'followers_count' => 0, // You can implement this later
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading profile stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile stats'
            ], 500);
        }
    }
}