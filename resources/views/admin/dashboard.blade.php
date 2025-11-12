<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bitsa Club</title>
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

        /* Admin Header */
        .admin-header {
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

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .admin-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Main Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
            padding-top: 80px;
        }

        /* Updated Sidebar Positioning */
        .admin-sidebar {
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

        .admin-sidebar.mobile-open {
            transform: translateX(0);
        }

        .admin-sidebar.collapsed {
            width: 80px;
            padding: 3rem 0.5rem 2rem;
        }

        .admin-sidebar.collapsed .nav-section h3,
        .admin-sidebar.collapsed .admin-info h3,
        .admin-sidebar.collapsed .admin-info p,
        .admin-sidebar.collapsed .nav-item span {
            display: none;
        }

        .admin-sidebar.collapsed .nav-item {
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

        .admin-main {
            flex: 1;
            margin-left: 0;
            padding: 2rem;
            transition: all 0.3s ease;
            min-height: calc(100vh - 80px);
            width: 100%;
        }

        /* Desktop sidebar behavior */
        @media (min-width: 769px) {
            .admin-sidebar {
                transform: translateX(0);
                height: calc(100vh - 80px);
                top: 80px;
            }

            .admin-main {
                margin-left: 280px;
            }

            .admin-main.expanded {
                margin-left: 80px;
            }

            .admin-sidebar.collapsed {
                transform: translateX(0);
            }
        }

        /* Admin Info */
        .admin-info {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-avatar {
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

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Tables */
        .table {
            background: var(--bg-card);
            border-radius: 8px;
            overflow: hidden;
        }

        .table th {
            background: var(--bg-secondary);
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background: var(--bg-secondary);
        }

        /* Badges */
        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-success {
            background: var(--accent-success);
            color: white;
        }

        .badge-warning {
            background: var(--accent-warning);
            color: white;
        }

        .badge-danger {
            background: var(--accent-danger);
            color: white;
        }

        .badge-secondary {
            background: var(--text-secondary);
            color: white;
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

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }

        .btn-primary {
            background: var(--accent-primary);
            color: white;
        }

        .btn-danger {
            background: var(--accent-danger);
            color: white;
        }

        .btn-warning {
            background: var(--accent-warning);
            color: white;
        }

        .btn-success {
            background: var(--accent-success);
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
            .admin-header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
                height: auto;
            }

            .admin-container {
                flex-direction: column;
                padding-top: 0;
            }

            .admin-sidebar {
                width: 280px;
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 1003;
                transition: transform 0.3s ease;
                padding-top: 5rem;
            }

            .admin-main {
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

            .admin-sidebar.collapsed {
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
        .nav-item,
        .admin-info h3,
        .admin-info p {
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
    </style>
</head>
<body>
    <!-- Mobile Sidebar Toggle -->
    <button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-brand">
            <i class="bi bi-shield-check"></i>
            <span>Admin Console</span>
        </div>
        <div class="admin-actions">
            <span class="text-muted">Welcome, {{ Auth::guard('admin')->user()->name }}</span>
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </header>

    <div class="admin-container">
        <!-- Admin Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-chevron-left" id="sidebarToggleIcon"></i>
            </button>

            <div class="admin-info">
                <div class="admin-avatar">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2)) }}
                </div>
                <h3>{{ Auth::guard('admin')->user()->name }}</h3>
            </div>

            <div class="nav-section">
                <h3>Dashboard</h3>
                <a href="#overview" class="nav-item active" data-section="overview">
                    <i class="bi bi-speedometer2"></i> <span>Overview</span>
                </a>
            </div>

            <div class="nav-section">
                <h3>Management</h3>
                <a href="#users" class="nav-item" data-section="users">
                    <i class="bi bi-people"></i> <span>User Management</span>
                </a>
                <a href="#posts" class="nav-item" data-section="posts">
                    <i class="bi bi-file-text"></i> <span>Post Management</span>
                </a>
                <a href="#meetings" class="nav-item" data-section="meetings">
                    <i class="bi bi-camera-video"></i> <span>Meeting Management</span>
                </a>
                <a href="#events" class="nav-item" data-section="events">
                    <i class="bi bi-calendar-event"></i> <span>Event Management</span>
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
        <div class="admin-main" id="adminMain">
            <!-- Overview Section -->
            <section id="overview-section" class="content-section active">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard Overview</h2>
                    <div class="text-muted">Last updated: {{ now()->format('M j, Y g:i A') }}</div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number text-primary">{{ $stats['total_users'] }}</div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-success">{{ $stats['total_posts'] }}</div>
                        <div class="stat-label">Total Posts</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-warning">{{ $stats['total_meetings'] }}</div>
                        <div class="stat-label">Total Meetings</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number text-info">{{ $stats['total_events'] }}</div>
                        <div class="stat-label">Total Events</div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Users -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Users</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Joined</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->created_at->format('M j, Y') }}</td>
                                                <td>
                                                    @if($user->is_active)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Suspended</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Posts</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Content</th>
                                                <th>User</th>
                                                <th>Posted</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_posts as $post)
                                            <tr>
                                                <td>{{ Str::limit($post->content, 50) }}</td>
                                                <td>{{ $post->user->name }}</td>
                                                <td>{{ $post->created_at->format('M j, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Upcoming Events</h3>
                    </div>
                    <div class="card-body">
                        @if($upcoming_events->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcoming_events as $event)
                                    <tr>
                                        <td>
                                            <strong>{{ $event->title }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($event->description, 60) }}</small>
                                        </td>
                                        <td>{{ $event->event_date->format('M j, Y') }} at {{ date('g:i A', strtotime($event->event_time)) }}</td>
                                        <td>{{ $event->location }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                            <h4 class="text-muted">No Upcoming Events</h4>
                            <p class="text-muted">Create events to display them here.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Include other sections directly since they might not exist -->
            <section id="users-section" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">User Management</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Posts</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->posts_count }}</td>
                                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Suspended</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->is_active)
                                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">Suspend</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Activate</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="posts-section" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Post Management</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Content</th>
                                        <th>User</th>
                                        <th>Likes</th>
                                        <th>Comments</th>
                                        <th>Posted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{ Str::limit($post->content, 80) }}</td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->likes_count }}</td>
                                        <td>{{ $post->comments_count }}</td>
                                        <td>{{ $post->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.posts.delete', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="meetings-section" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Meeting Management</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Host</th>
                                        <th>Scheduled</th>
                                        <th>Participants</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meetings as $meeting)
                                    <tr>
                                        <td>{{ $meeting->title }}</td>
                                        <td>{{ $meeting->user->name }}</td>
                                        <td>{{ $meeting->scheduled_time->format('M j, Y g:i A') }}</td>
                                        <td>{{ $meeting->participants_count }}</td>
                                        <td>
                                            <span class="badge {{ $meeting->type === 'public' ? 'badge-success' : 'badge-warning' }}">
                                                {{ ucfirst($meeting->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $meeting->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $meeting->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.meetings.delete', $meeting) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this meeting?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section id="events-section" class="content-section">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="card-title">Event Management</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
                            <i class="bi bi-plus-circle"></i> Create Event
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Date & Time</th>
                                        <th>Location</th>
                                        <th>Max Attendees</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ Str::limit($event->description, 60) }}</td>
                                        <td>{{ $event->event_date->format('M j, Y') }} at {{ date('g:i A', strtotime($event->event_time)) }}</td>
                                        <td>{{ $event->location }}</td>
                                        <td>{{ $event->max_attendees ?? 'Unlimited' }}</td>
                                        <td>
                                            <span class="badge {{ $event->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $event->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm">Edit</button>
                                            <form action="{{ route('admin.events.delete', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.events.create') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Location</label>
                                    <input type="text" class="form-control" name="location" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Date</label>
                                    <input type="date" class="form-control" name="event_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Time</label>
                                    <input type="time" class="form-control" name="event_time" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maximum Attendees (Optional)</label>
                            <input type="number" class="form-control" name="max_attendees" min="1">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar functionality
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const adminSidebar = document.getElementById('adminSidebar');

        mobileSidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', function() {
            adminSidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        });

        // Desktop sidebar toggle
        const adminMain = document.getElementById('adminMain');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');

        if (window.innerWidth > 768) {
            sidebarToggle.addEventListener('click', function() {
                adminSidebar.classList.toggle('collapsed');
                adminMain.classList.toggle('expanded');
                
                if (adminSidebar.classList.contains('collapsed')) {
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
                    adminSidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                }
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

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                sidebarToggle.style.display = 'none';
                adminSidebar.classList.remove('collapsed');
                adminMain.classList.remove('expanded');
            } else {
                sidebarToggle.style.display = 'flex';
                adminSidebar.classList.remove('mobile-open');
                sidebarOverlay.classList.remove('active');
            }
        });

        // Auto-set event date to tomorrow
        document.addEventListener('DOMContentLoaded', function() {
            const eventDateInput = document.querySelector('input[name="event_date"]');
            if (eventDateInput) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                eventDateInput.min = tomorrow.toISOString().split('T')[0];
            }
        });

        // Show alerts
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.admin-main').insertBefore(alert, document.querySelector('.admin-main').firstChild);
            
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>