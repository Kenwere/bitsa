<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Bitsa Club</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-sidebar: #1e293b;
            --bg-card: #2a2a2a;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --accent-primary: #2563eb;
            --accent-secondary: #7c3aed;
            --accent-success: #10b981;
            --accent-warning: #f59e0b;
            --accent-danger: #ef4444;
            --border-color: #404040;
        }

        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-sidebar: #1e40af;
            --bg-card: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent-primary: #2563eb;
            --accent-secondary: #7c3aed;
            --accent-success: #10b981;
            --accent-warning: #f59e0b;
            --accent-danger: #ef4444;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* User Header */
        .user-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            z-index: 1002;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Main Layout */
        .user-container {
            display: flex;
            min-height: 100vh;
            padding-top: 80px;
        }

        /* Updated Sidebar Positioning */
        .user-sidebar {
            width: 280px;
            background: var(--bg-sidebar);
            padding: 3rem 1.5rem 2rem;
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1003; /* Higher than header */
            transform: translateX(-100%);
        }

        .user-sidebar.mobile-open {
            transform: translateX(0);
        }

        .user-sidebar.collapsed {
            width: 80px;
            padding: 3rem 0.5rem 2rem;
        }

        .user-sidebar.collapsed .nav-section h3,
        .user-sidebar.collapsed .user-info h3,
        .user-sidebar.collapsed .user-info p,
        .user-sidebar.collapsed .nav-item span {
            display: none;
        }

        .user-sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: -12px;
            background: var(--accent-primary);
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 0.8rem;
            z-index: 1004;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .user-main {
            flex: 1;
            margin-left: 0;
            padding: 2rem;
            transition: all 0.3s ease;
            min-height: calc(100vh - 80px);
            width: 100%;
        }

        /* Desktop sidebar behavior */
        @media (min-width: 769px) {
            .user-sidebar {
                transform: translateX(0);
                height: calc(100vh - 80px);
                top: 80px;
            }

            .user-main {
                margin-left: 280px;
            }

            .user-main.expanded {
                margin-left: 80px;
            }

            .user-sidebar.collapsed {
                transform: translateX(0);
            }
        }

        /* User Info */
        .user-info {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
            color: white;
            font-weight: 600;
        }

        /* Navigation */
        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section h3 {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        [data-theme="light"] .nav-section h3 {
            color: #ffffff;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 0.5rem;
            cursor: pointer;
        }

        [data-theme="light"] .nav-item {
            color: #ffffff;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Content Areas */
        .content-section {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--accent-primary);
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            color: white;
        }

        .btn-success {
            background: var(--accent-success);
            color: white;
        }

        .btn-warning {
            background: var(--accent-warning);
            color: white;
        }

        .btn-danger {
            background: var(--accent-danger);
            color: white;
        }

        /* Forms */
        .form-control {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-control:focus {
            background: var(--bg-secondary);
            border-color: var(--accent-primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Modal Styles */
        .modal-content {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .btn-close {
            filter: invert(1);
        }

        [data-theme="light"] .btn-close {
            filter: invert(0);
        }

        /* Mobile Sidebar */
        .mobile-sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1004;
            background: var(--accent-primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem;
            font-size: 1.2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .user-header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
                height: auto;
            }

            .user-container {
                flex-direction: column;
                padding-top: 0;
            }

            .user-sidebar {
                width: 280px;
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 1003;
                transition: transform 0.3s ease;
                padding-top: 5rem;
            }

            .user-main {
                margin-left: 0;
                padding: 1rem;
                min-height: auto;
                width: 100%;
            }

            .sidebar-toggle {
                display: none;
            }

            .mobile-sidebar-toggle {
                display: block;
            }

            .user-sidebar.collapsed {
                width: 80px;
            }

            /* Overlay for mobile */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1002;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Ensure text visibility in both themes */
        .card-title,
        .stat-number,
        .stat-label,
        .table th,
        .table td,
        .user-info h3,
        .user-info p {
            color: var(--text-primary) !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        .form-label {
            color: var(--text-primary) !important;
        }

        .table {
            color: var(--text-primary) !important;
        }

        .table th {
            color: var(--text-primary) !important;
            background: var(--bg-secondary) !important;
        }

        .table td {
            color: var(--text-primary) !important;
            background: var(--bg-card) !important;
        }

        .table tbody tr:hover td {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        /* Post Styles */
        .post-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .post-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
        }

        .post-user-info {
            flex: 1;
        }

        .post-user {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .post-username {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .post-time {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .post-content {
            margin-bottom: 1rem;
            line-height: 1.6;
            white-space: pre-line;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        .post-image {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 1rem;
            max-height: 400px;
            object-fit: cover;
        }

        .post-actions {
            display: flex;
            gap: 1rem;
            border-top: 1px solid var(--border-color);
            padding-top: 1rem;
        }

        .post-action {
            background: none;
            border: none;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .post-action:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--accent-primary);
        }

        .post-action.liked {
            color: var(--accent-danger);
        }

        /* Comments Section */
        .comments-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .comment-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .comment-input {
            flex: 1;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 0.5rem 1rem;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .comment-input::placeholder {
            color: var(--text-secondary);
        }

        .comment-input:focus {
            outline: none;
            border-color: var(--accent-primary);
        }

        .comments-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .comment {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
        }

        .comment-content {
            flex: 1;
        }

        .comment-user {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .comment-username {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-left: 0.5rem;
        }

        .comment-text {
            margin: 0.25rem 0;
            font-size: 0.9rem;
            color: var(--text-primary);
            line-height: 1.4;
        }

        .comment-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Create Post Form */
        .create-post {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Profile Update Button */
        .btn-update-profile {
            background: var(--accent-primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-update-profile:hover {
            background: #1d4ed8;
            color: white;
            transform: translateY(-1px);
        }

        /* New Meeting Button */
        .btn-new-meeting {
            background: var(--accent-primary);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-new-meeting:hover {
            background: #1d4ed8;
            color: white;
            transform: translateY(-1px);
        }

        /* Loading States */
        .loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle -->
    <button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- User Header -->
    <header class="user-header">
        <div class="user-brand">
            <i class="bi bi-people"></i>
            <span>Bitsa Club</span>
        </div>
        <div class="user-actions">
            <span class="text-muted">Welcome, {{ Auth::user()->name }}</span>
            <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <div class="user-container">
        <!-- User Sidebar -->
        <div class="user-sidebar" id="userSidebar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-chevron-left" id="sidebarToggleIcon"></i>
            </button>

            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <h3>{{ Auth::user()->name }}</h3>
                <p class="text-muted">@{{ Auth::user()->username }}</p>
            </div>

            <div class="nav-section">
                <h3>Dashboard</h3>
                <a href="#feed" class="nav-item active" data-section="feed">
                    <i class="bi bi-house"></i> <span>Community Feed</span>
                </a>
            </div>

            <div class="nav-section">
                <h3>Features</h3>
                <a href="#events" class="nav-item" data-section="events">
                    <i class="bi bi-calendar-event"></i> <span>Events</span>
                </a>
                <a href="#meetings" class="nav-item" data-section="meetings">
                    <i class="bi bi-camera-video"></i> <span>Meetings</span>
                </a>
            </div>

            <div class="nav-section">
                <h3>Account</h3>
                <a href="#profile" class="nav-item" data-section="profile">
                    <i class="bi bi-person"></i> <span>My Profile</span>
                </a>
                <a href="#settings" class="nav-item" data-section="settings">
                    <i class="bi bi-gear"></i> <span>Settings</span>
                </a>
            </div>

            <div class="nav-section">
                <h3>Theme</h3>
                <button class="nav-item w-100 text-start" id="themeToggle" style="background: none; border: none;">
                    <i class="bi bi-moon" id="themeIcon"></i> 
                    <span id="themeText">Dark Mode</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="user-main" id="userMain">
            <!-- Feed Section -->
            <section id="feed-section" class="content-section active">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="bi bi-house"></i> Community Feed</h2>
                    </div>
                    
                    <!-- Create Post -->
                    <div class="create-post">
                        <form id="createPostForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" id="postContent" name="content" placeholder="What's on your mind?" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control" id="postImage" name="image" accept="image/*">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" class="btn btn-sm me-2" style="background: var(--accent-secondary); color: white;" onclick="document.getElementById('postImage').click()">
                                        <i class="bi bi-image"></i> Photo
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary" id="postButton">
                                    <i class="bi bi-send"></i> Post
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Posts Feed -->
                    <div id="postsFeed">
                        <div class="loading">
                            <i class="bi bi-arrow-repeat spinner"></i> Loading posts...
                        </div>
                    </div>
                </div>
            </section>

            <!-- Events Section -->
            <section id="events-section" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Events</h2>
                    </div>
                    
                    <div class="card-body">
                        <div id="eventsList">
                            <div class="loading">
                                <i class="bi bi-arrow-repeat spinner"></i> Loading events...
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Meetings Section -->
            <section id="meetings-section" class="content-section">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="card-title mb-0"><i class="bi bi-camera-video"></i> Live Meetings</h2>
                        <button class="btn btn-new-meeting" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                            <i class="bi bi-plus-circle"></i> New Meeting
                        </button>
                    </div>
                    
                    <!-- Active Meetings -->
                    <div class="mb-4">
                        <h3 class="mb-3">
                            Active Meetings
                            <small class="text-muted" id="activeMeetingsCount"></small>
                        </h3>
                        <div id="activeMeetings" class="row">
                            <div class="col-12">
                                <div class="loading">
                                    <i class="bi bi-arrow-repeat spinner"></i> Loading active meetings...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduled Meetings -->
                    <div>
                        <h3 class="mb-3">
                            Scheduled Meetings
                            <small class="text-muted" id="scheduledMeetingsCount"></small>
                        </h3>
                        <div id="scheduledMeetings">
                            <div class="loading">
                                <i class="bi bi-arrow-repeat spinner"></i> Loading scheduled meetings...
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Profile Section -->
            <section id="profile-section" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="bi bi-person"></i> My Profile</h2>
                    </div>
                    
                    <div class="row">
                        <!-- Profile Info -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="user-avatar-large mb-3">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <h3 id="profileName">{{ Auth::user()->name }}</h3>
                                    <p class="text-muted" id="profileUsername">@{{ Auth::user()->username }}</p>
                                    <p class="text-muted" id="profileEmail">{{ Auth::user()->email }}</p>
                                    
                                    <div class="stats mt-4">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="stat-number" id="postsCount">0</div>
                                                <div class="stat-label">Posts</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="stat-number" id="followingCount">0</div>
                                                <div class="stat-label">Following</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="stat-number" id="followersCount">0</div>
                                                <div class="stat-label">Followers</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Details -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Profile Information</h3>
                                </div>
                                <div class="card-body">
                                    <form id="profileForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" class="form-control profile-input" name="name" value="{{ Auth::user()->name }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" class="form-control profile-input" name="username" value="{{ Auth::user()->username }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control profile-input" value="{{ Auth::user()->email }}" disabled>
                                            <small class="text-muted">Email cannot be changed</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Bio</label>
                                            <textarea class="form-control profile-input" name="bio" rows="3" placeholder="Tell us about yourself..."></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Skills</label>
                                            <input type="text" class="form-control profile-input" name="skills" placeholder="Add your skills (comma separated)">
                                            <small class="text-muted">Separate multiple skills with commas</small>
                                        </div>
                                        <button type="submit" class="btn btn-update-profile" id="updateProfileBtn">
                                            <i class="bi bi-check-circle"></i> Update Profile
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Settings Section -->
            <section id="settings-section" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="bi bi-gear"></i> Settings</h2>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Settings feature coming soon!
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Create Meeting Modal -->
    <div class="modal fade" id="createMeetingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createMeetingForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Meeting Title *</label>
                            <input type="text" class="form-control" name="title" required placeholder="Enter meeting title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Describe the purpose of this meeting"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date *</label>
                                    <input type="date" class="form-control" name="date" required id="meetingDate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Time *</label>
                                    <input type="time" class="form-control" name="time" required id="meetingTime">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meeting Type *</label>
                            <select class="form-control" name="type" required>
                                <option value="public">Public - Anyone can join</option>
                                <option value="private">Private - Only invited users can join</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="createMeeting()" id="createMeetingBtn">
                        <i class="bi bi-plus-circle"></i> Create Meeting
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar functionality
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const userSidebar = document.getElementById('userSidebar');

        mobileSidebarToggle.addEventListener('click', function() {
            userSidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            userSidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        });

        // Desktop sidebar toggle
        const userMain = document.getElementById('userMain');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');

        if (window.innerWidth > 768) {
            sidebarToggle.addEventListener('click', function() {
                userSidebar.classList.toggle('collapsed');
                userMain.classList.toggle('expanded');
                
                if (userSidebar.classList.contains('collapsed')) {
                    sidebarToggleIcon.className = 'bi bi-chevron-right';
                } else {
                    sidebarToggleIcon.className = 'bi bi-chevron-left';
                }
            });
        } else {
            sidebarToggle.style.display = 'none';
        }

        // Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.tagName === 'BUTTON' && this.type === 'submit') return;
                
                e.preventDefault();
                const section = this.getAttribute('data-section');
                
                document.querySelectorAll('.nav-item').forEach(nav => {
                    nav.classList.remove('active');
                });
                this.classList.add('active');
                
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });
                document.getElementById(section + '-section').classList.add('active');
                
                // Close mobile sidebar
                if (window.innerWidth <= 768) {
                    userSidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                }
                
                // Load content for the active section
                loadSectionContent(section);
            });
        });

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const themeText = document.getElementById('themeText');
        const html = document.documentElement;

        const currentTheme = localStorage.getItem('theme') || 'dark';
        html.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);

        themeToggle.addEventListener('click', () => {
            const theme = html.getAttribute('data-theme');
            const newTheme = theme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun';
                themeText.textContent = 'Light Mode';
            } else {
                themeIcon.className = 'bi bi-moon';
                themeText.textContent = 'Dark Mode';
            }
        }

        // Load section content
        function loadSectionContent(section) {
            switch(section) {
                case 'feed':
                    if (typeof loadPosts === 'function') loadPosts();
                    break;
                case 'events':
                    if (typeof loadEvents === 'function') loadEvents();
                    break;
                case 'meetings':
                    if (typeof loadMeetings === 'function') loadMeetings();
                    break;
                case 'profile':
                    if (typeof loadProfileData === 'function') {
                        loadProfileData();
                        loadProfileStats();
                    }
                    break;
            }
        }

        // Show alerts
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.user-main').insertBefore(alert, document.querySelector('.user-main').firstChild);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        // Initialize meeting modal dates
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today for meeting form
            const dateInput = document.getElementById('meetingDate');
            if (dateInput) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.min = today;
                dateInput.value = today;
            }

            // Set default time to next hour
            const timeInput = document.getElementById('meetingTime');
            if (timeInput) {
                const nextHour = new Date();
                nextHour.setHours(nextHour.getHours() + 1);
                nextHour.setMinutes(0);
                timeInput.value = nextHour.toTimeString().substring(0, 5);
            }

            // Load initial section
            loadSectionContent('feed');
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                sidebarToggle.style.display = 'none';
                userSidebar.classList.remove('collapsed');
                userMain.classList.remove('expanded');
            } else {
                sidebarToggle.style.display = 'flex';
                userSidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');
            }
        });
    </script>

    <!-- Include the JavaScript functionality -->
    <script>
        let isLoadingPosts = false;

        // Post functionality
        document.getElementById('createPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            createPost();
        });

        async function createPost() {
            const content = document.getElementById('postContent').value.trim();
            const imageFile = document.getElementById('postImage').files[0];
            const postButton = document.getElementById('postButton');
            
            if (!content && !imageFile) {
                showAlert('Please add some content or an image to your post.', 'danger');
                return;
            }

            postButton.disabled = true;
            postButton.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Posting...';

            const formData = new FormData();
            formData.append('content', content);
            if (imageFile) {
                formData.append('image', imageFile);
            }
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            try {
                const response = await fetch('/api/posts', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('postContent').value = '';
                    document.getElementById('postImage').value = '';
                    showAlert('Post created successfully!', 'success');
                    loadPosts();
                } else {
                    const errorMessages = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                    showAlert(errorMessages, 'danger');
                }
            } catch (error) {
                console.error('Error creating post:', error);
                showAlert('Network error. Please check your connection and try again.', 'danger');
            } finally {
                postButton.disabled = false;
                postButton.innerHTML = '<i class="bi bi-send"></i> Post';
            }
        }

        async function loadPosts() {
            if (isLoadingPosts) return;
            
            isLoadingPosts = true;
            const feed = document.getElementById('postsFeed');
            feed.innerHTML = '<div class="loading"><i class="bi bi-arrow-repeat spinner"></i> Loading posts...</div>';

            try {
                const response = await fetch('/api/posts', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const posts = await response.json();

                if (!Array.isArray(posts)) {
                    throw new Error('Invalid response format from server');
                }

                if (posts.length === 0) {
                    feed.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                            <h4 class="text-muted">Nothing here yet</h4>
                            <p class="text-muted">Be the first to share something with the community!</p>
                        </div>
                    `;
                    return;
                }

                feed.innerHTML = posts.map(post => `
                    <div class="post-card" id="post-${post.id}">
                        <div class="post-header">
                            <div class="post-avatar">
                                ${post.user.avatar}
                            </div>
                            <div class="post-user-info">
                                <div class="post-user">${post.user.name} <span class="post-username">@${post.user.username}</span></div>
                                <div class="post-time">${formatTime(post.created_at)}</div>
                            </div>
                        </div>
                        
                        ${post.content ? `<div class="post-content">${escapeHtml(post.content)}</div>` : ''}
                        
                        ${post.image ? `<img src="${post.image}" class="post-image" alt="Post image" onerror="this.style.display='none'">` : ''}
                        
                        <div class="post-actions">
                            <button class="post-action ${post.liked ? 'liked' : ''}" onclick="likePost(${post.id})">
                                <i class="bi ${post.liked ? 'bi-heart-fill' : 'bi-heart'}"></i> 
                                <span>${post.likes}</span>
                            </button>
                            <button class="post-action" onclick="toggleComments(${post.id})">
                                <i class="bi bi-chat"></i> 
                                <span>${post.comments.length}</span>
                            </button>
                        </div>
                    </div>
                `).join('');

            } catch (error) {
                console.error('Error loading posts:', error);
                feed.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Failed to load posts. Please try again later.
                    </div>
                `;
            } finally {
                isLoadingPosts = false;
            }
        }

        // Comment functionality
        function toggleComments(postId) {
            const commentsSection = document.getElementById(`comments-${postId}`);
            if (!commentsSection) {
                createCommentsSection(postId);
            } else {
                commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
            }
        }

        function createCommentsSection(postId) {
            const postElement = document.getElementById(`post-${postId}`);
            if (!postElement) return;

            const commentsSection = document.createElement('div');
            commentsSection.className = 'comments-section';
            commentsSection.id = `comments-${postId}`;
            commentsSection.innerHTML = `
                <div class="comment-form">
                    <input type="text" class="comment-input" placeholder="Write a comment..." id="comment-input-${postId}">
                    <button class="btn btn-sm" style="background: var(--accent-primary); color: white;" onclick="addComment(${postId})">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
                <div id="comments-list-${postId}" class="comments-list">
                    <div class="loading">Loading comments...</div>
                </div>
            `;

            const postActions = postElement.querySelector('.post-actions');
            postActions.parentNode.insertBefore(commentsSection, postActions.nextSibling);

            loadComments(postId);
        }

        async function loadComments(postId) {
            try {
                // Load comments for this post
                // For now, we'll use the comments from the initial post data
                const postElement = document.getElementById(`post-${postId}`);
                if (postElement) {
                    // You can implement API call here to fetch comments
                    // For now, we'll use the initial data
                    const postData = await getPostData(postId);
                    if (postData && postData.comments) {
                        displayComments(postId, postData.comments);
                    }
                }
            } catch (error) {
                console.error('Error loading comments:', error);
            }
        }

        async function getPostData(postId) {
            try {
                const response = await fetch(`/api/posts/${postId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    return await response.json();
                }
            } catch (error) {
                console.error('Error fetching post data:', error);
            }
            return null;
        }

        function displayComments(postId, comments) {
            const commentsList = document.getElementById(`comments-list-${postId}`);
            if (!commentsList) return;

            if (comments.length === 0) {
                commentsList.innerHTML = '<div class="text-muted text-center py-2">No comments yet</div>';
                return;
            }

            commentsList.innerHTML = comments.map(comment => `
                <div class="comment">
                    <div class="comment-avatar">
                        ${comment.user.name.substring(0, 2).toUpperCase()}
                    </div>
                    <div class="comment-content">
                        <div class="comment-user">${comment.user.name} <span class="comment-username">@${comment.user.username}</span></div>
                        <div class="comment-text">${escapeHtml(comment.content)}</div>
                        <div class="comment-time">${formatTime(comment.created_at)}</div>
                    </div>
                </div>
            `).join('');
        }

        async function addComment(postId) {
            const commentInput = document.getElementById(`comment-input-${postId}`);
            const content = commentInput.value.trim();
            
            if (!content) {
                showAlert('Please enter a comment', 'warning');
                return;
            }

            try {
                const response = await fetch(`/api/posts/${postId}/comment`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ content: content }),
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    commentInput.value = '';
                    showAlert('Comment added successfully!', 'success');
                    loadComments(postId);
                    updateCommentCount(postId, data.commentCount || 1);
                } else {
                    showAlert(data.message || 'Failed to add comment', 'danger');
                }
            } catch (error) {
                console.error('Error adding comment:', error);
                showAlert('Network error. Please try again.', 'danger');
            }
        }

        function updateCommentCount(postId, count) {
            const commentButton = document.querySelector(`#post-${postId} .post-action:nth-child(2) span`);
            if (commentButton) {
                commentButton.textContent = count;
            }
        }

        // Utility functions
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);

            if (minutes < 1) return 'Just now';
            if (minutes < 60) return `${minutes}m ago`;
            if (hours < 24) return `${hours}h ago`;
            if (days < 7) return `${days}d ago`;
            
            return date.toLocaleDateString();
        }

        // Auto-refresh posts every 30 seconds
        setInterval(() => {
            if (document.getElementById('feed-section').classList.contains('active') && !isLoadingPosts) {
                loadPosts();
            }
        }, 30000);
    </script>
</body>
</html>