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