<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// User Authentication
Route::get('/user/register', function () {
    return view('auth.user-register');
})->name('user.register');

Route::post('/user/register', [UserAuthController::class, 'register']);
Route::get('/user/login', function () {
    return view('auth.user-login');
})->name('user.login');
Route::post('/user/login', [UserAuthController::class, 'login'])->name('user.login.submit');

// Admin Authentication
Route::get('/admin/register', function () {
    return view('auth.admin-register');
})->name('admin.register');

Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

// User Dashboard (Protected)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
    
    Route::post('/user/logout', [UserAuthController::class, 'logout'])->name('user.logout');
    
    // API routes for dashboard functionality
    Route::prefix('api')->group(function () {
        // Post routes
        Route::get('/posts', [PostController::class, 'index']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::post('/posts/{post}/like', [PostController::class, 'like']);
        Route::post('/posts/{post}/comment', [PostController::class, 'comment']);
        
        // Meeting routes
        Route::get('/meetings', [MeetingController::class, 'index']);
        Route::post('/meetings', [MeetingController::class, 'store']);
        Route::post('/meetings/{meeting}/join', [MeetingController::class, 'join']);
        Route::post('/meetings/{meeting}/leave', [MeetingController::class, 'leave']);
        Route::post('/meetings/{meeting}/rsvp', [MeetingController::class, 'rsvp']);
        Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy']);
        
        // Profile routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::get('/profile/stats', [ProfileController::class, 'stats']);
        
        // Events routes
        Route::get('/events', [EventController::class, 'index']);
    });
    
    // Meeting room route
    Route::get('/meeting/{meeting}', function (App\Models\Meeting $meeting) {
        if ($meeting->type === 'private' && !$meeting->participants()->where('user_id', auth()->id())->exists()) {
            abort(403, 'This is a private meeting.');
        }
        
        if (!$meeting->is_active || $meeting->scheduled_time->gt(now())) {
            abort(403, 'Meeting is not active yet.');
        }
        
        return view('meeting-room', compact('meeting'));
    })->name('meeting.room');
});

// Admin Dashboard (Protected)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // In web.php, add to admin routes
Route::post('/admin/meetings', [AdminController::class, 'createMeeting'])->name('admin.meetings.create');

    // Admin Management Routes
    Route::prefix('admin')->group(function () {
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
        Route::post('/users/{user}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        // Post Management
        Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts');
        Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])->name('admin.posts.delete');
        
        // Meeting Management
        Route::get('/meetings', [AdminController::class, 'meetings'])->name('admin.meetings');
        Route::delete('/meetings/{meeting}', [AdminController::class, 'deleteMeeting'])->name('admin.meetings.delete');
        
        // Event Management
        Route::get('/events', [AdminController::class, 'events'])->name('admin.events');
        Route::post('/events', [AdminController::class, 'createEvent'])->name('admin.events.create');
        Route::put('/events/{event}', [AdminController::class, 'updateEvent'])->name('admin.events.update');
        Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('admin.events.delete');
    });
});
