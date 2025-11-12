<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        try {
            Log::info('=== POSTS INDEX START ===');
            Log::info('Authenticated: ' . (auth()->check() ? 'YES' : 'NO'));
            Log::info('User ID: ' . auth()->id());
            
            // Get all posts with relationships
            $posts = Post::with(['user', 'comments.user', 'likes'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Total posts found: ' . $posts->count());
            
            $formattedPosts = $posts->map(function ($post) {
                try {
                    // Get user info - FIXED: Include username properly
                    $userName = $post->user ? $post->user->name : 'Unknown User';
                    $userUsername = $post->user ? $post->user->username : 'unknown';
                    $userAvatar = strtoupper(substr($userName, 0, 2));
                    
                    // Check if current user liked this post
                    $liked = false;
                    if (Auth::check()) {
                        $liked = $post->likes->contains('user_id', Auth::id());
                    }
                    
                    // Format comments
                    $formattedComments = $post->comments->map(function ($comment) {
                        $commentUserName = $comment->user ? $comment->user->name : 'Unknown User';
                        $commentUserUsername = $comment->user ? $comment->user->username : 'unknown';
                        
                        return [
                            'id' => $comment->id,
                            'user' => [
                                'name' => $commentUserName,
                                'username' => $commentUserUsername
                            ],
                            'content' => $comment->content,
                            'created_at' => $comment->created_at->toISOString()
                        ];
                    });
                    
                    return [
                        'id' => $post->id,
                        'user' => [
                            'name' => $userName,
                            'username' => $userUsername, // This is now properly included
                            'avatar' => $userAvatar
                        ],
                        'content' => $post->content ?? '',
                        'image' => $post->image ? Storage::url($post->image) : null,
                        'created_at' => $post->created_at->toISOString(),
                        'likes' => $post->likes_count ?? 0,
                        'comments' => $formattedComments->toArray(),
                        'liked' => $liked
                    ];
                    
                } catch (\Exception $e) {
                    Log::error("Error formatting post {$post->id}: " . $e->getMessage());
                    return null;
                }
            })->filter();
            
            Log::info('Successfully formatted posts: ' . $formattedPosts->count());
            Log::info('=== POSTS INDEX END ===');
            
            return response()->json($formattedPosts->values());
            
        } catch (\Exception $e) {
            Log::error('Error in PostController@index: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load posts',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ... rest of your PostController methods remain the same
    public function store(Request $request)
    {
        try {
            Log::info('=== CREATING NEW POST ===');
            
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'content' => 'nullable|string|max:1000',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if we have content or image
            if (empty($request->content) && !$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post must have content or an image'
                ], 422);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
                Log::info('Image stored at: ' . $imagePath);
            }

            // Create the post
            $postData = [
                'user_id' => Auth::id(),
                'content' => $request->content,
                'image' => $imagePath,
                'likes_count' => 0,
                'comments_count' => 0
            ];
            
            Log::info('Creating post with data: ', $postData);
            
            $post = Post::create($postData);
            Log::info('Post created with ID: ' . $post->id);

            // Load the user relationship with username
            $post->load('user');
            
            $response = [
                'success' => true,
                'post' => [
                    'id' => $post->id,
                    'user' => [
                        'name' => $post->user->name,
                        'username' => $post->user->username, // Include username
                        'avatar' => strtoupper(substr($post->user->name, 0, 2))
                    ],
                    'content' => $post->content,
                    'image' => $post->image ? Storage::url($post->image) : null,
                    'created_at' => $post->created_at->toISOString(),
                    'likes' => 0,
                    'comments' => [],
                    'liked' => false
                ]
            ];
            
            Log::info('=== POST CREATED SUCCESSFULLY ===');
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function like(Post $post)
    {
        try {
            Log::info('=== LIKING POST ===');
            Log::info('Post ID: ' . $post->id);
            Log::info('User ID: ' . Auth::id());
            
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $like = PostLike::where('user_id', Auth::id())
                ->where('post_id', $post->id)
                ->first();

            if ($like) {
                $like->delete();
                $liked = false;
                $post->decrement('likes_count');
                Log::info('Post unliked');
            } else {
                PostLike::create([
                    'user_id' => Auth::id(),
                    'post_id' => $post->id
                ]);
                $liked = true;
                $post->increment('likes_count');
                Log::info('Post liked');
            }

            $updatedLikesCount = $post->likes_count;

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $updatedLikesCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error liking post: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to like post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function comment(Request $request, Post $post)
    {
        try {
            Log::info('=== ADDING COMMENT ===');
            Log::info('Post ID: ' . $post->id);
            Log::info('Comment content: ' . $request->content);
            
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'content' => $request->content
            ]);

            $post->increment('comments_count');
            $comment->load('user');

            Log::info('Comment added successfully');

            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'user' => [
                        'name' => $comment->user->name,
                        'username' => $comment->user->username, // Include username
                        'avatar' => strtoupper(substr($comment->user->name, 0, 2))
                    ],
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error adding comment: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }
}