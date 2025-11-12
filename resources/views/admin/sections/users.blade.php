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
                            <td>{{ $user->posts_count ?? 0 }}</td>
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