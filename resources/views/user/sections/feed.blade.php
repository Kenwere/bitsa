<section id="feed-section" class="content-section active">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="bi bi-house"></i> Community Feed</h2>
        </div>
        
        <!-- Create Post -->
        <div class="create-post mb-4">
            <form id="createPostForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <textarea class="form-control" id="postContent" name="content" placeholder="What's on your mind?" rows="3" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);"></textarea>
                </div>
                <div class="mb-3">
                    <input type="file" class="form-control" id="postImage" name="image" accept="image/*" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-sm me-2" style="background: var(--accent-cyan); color: #000;" onclick="document.getElementById('postImage').click()">
                            <i class="bi bi-image"></i> Photo
                        </button>
                    </div>
                    <button type="submit" class="btn" style="background: var(--accent-pink); color: #fff;" id="postButton">
                        <i class="bi bi-send"></i> Post
                    </button>
                </div>
            </form>
        </div>

        <!-- Posts Feed -->
        <div id="postsFeed">
            <div class="text-center text-muted py-4">
                <i class="bi bi-arrow-repeat spinner"></i> Loading posts...
            </div>
        </div>
    </div>
</section>

<style>
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: var(--accent-cyan);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: var(--accent-purple);
    }

    .post-card {
        background: rgba(255,255,255,0.05);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text-primary);
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
        background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-weight: bold;
        color: #000;
    }

    .post-user {
        font-weight: 600;
        color: var(--text-primary);
    }

    .post-time {
        font-size: 0.8rem;
        color: var(--text-primary);
        opacity: 0.7;
    }

    .post-content {
        margin-bottom: 1rem;
        line-height: 1.6;
        white-space: pre-line;
        color: var(--text-primary);
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
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 1rem;
    }

    .post-action {
        background: none;
        border: none;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
    }

    .post-action:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--accent-cyan);
    }

    .post-action.liked {
        color: var(--accent-pink);
    }

    .comments-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .comment-form {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .comment-input {
        flex: 1;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        color: var(--text-primary);
    }

    .comment-input::placeholder {
        color: rgba(255,255,255,0.5);
    }

    .comment {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        color: var(--text-primary);
    }

    .comment-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        flex-shrink: 0;
        font-weight: bold;
        color: #000;
    }

    .comment-content {
        flex: 1;
    }

    .comment-user {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    .comment-text {
        margin: 0.25rem 0;
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    .comment-time {
        font-size: 0.75rem;
        color: var(--text-primary);
        opacity: 0.7;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem;
        margin-bottom: 1rem;
        animation: slideDown 0.3s ease;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.2);
        color: #d4edda;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #f8d7da;
        border-left: 4px solid #dc3545;
    }

    .loading {
        text-align: center;
        padding: 2rem;
        color: var(--text-primary);
        opacity: 0.7;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-primary);
        opacity: 0.7;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Dark mode text fixes */
    [data-theme="dark"] .text-muted {
        color: #adb5bd !important;
    }

    [data-theme="dark"] .post-time,
    [data-theme="dark"] .comment-time {
        color: #adb5bd !important;
    }

    [data-theme="dark"] .form-control {
        color: #ffffff !important;
    }

    [data-theme="dark"] .form-control::placeholder {
        color: #adb5bd !important;
    }

    [data-theme="dark"] .comment-input {
        color: #ffffff !important;
    }

    [data-theme="dark"] .comment-input::placeholder {
        color: #adb5bd !important;
    }

    [data-theme="dark"] .loading,
    [data-theme="dark"] .empty-state {
        color: #adb5bd !important;
    }
</style>

<script>
// Post creation and management with actual API calls
document.getElementById('createPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    createPost();
});

async function createPost() {
    const content = document.getElementById('postContent').value.trim();
    const imageFile = document.getElementById('postImage').files[0];
    const postButton = document.getElementById('postButton');
    
    console.log('Creating post with content:', content, 'and image:', imageFile);
    
    if (!content && !imageFile) {
        showTimedAlert('Please add some content or an image to your post.', 'danger');
        return;
    }

    // Disable button and show loading state
    postButton.disabled = true;
    postButton.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Posting...';

    const formData = new FormData();
    formData.append('content', content);
    if (imageFile) {
        formData.append('image', imageFile);
    }
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    try {
        console.log('Sending POST request to /api/posts');
        const response = await fetch('/api/posts', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);

        if (data.success) {
            document.getElementById('postContent').value = '';
            document.getElementById('postImage').value = '';
            showTimedAlert('Post created successfully!', 'success');
            // Add the new post to the feed immediately
            addPostToFeed(data.post);
        } else {
            // Handle validation errors
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join(', ');
                showTimedAlert(errorMessages, 'danger');
            } else {
                showTimedAlert(data.message || 'Failed to create post. Please try again.', 'danger');
            }
        }
    } catch (error) {
        console.error('Error creating post:', error);
        showTimedAlert('Network error. Please check your connection and try again.', 'danger');
    } finally {
        // Re-enable button
        postButton.disabled = false;
        postButton.innerHTML = '<i class="bi bi-send"></i> Post';
    }
}

// Function to add a single post to the feed
function addPostToFeed(post) {
    console.log('Adding post to feed:', post);
    const feed = document.getElementById('postsFeed');
    const emptyState = feed.querySelector('.empty-state');
    
    // Remove empty state if it exists
    if (emptyState) {
        emptyState.remove();
    }
    
    // Remove loading state if it exists
    const loading = feed.querySelector('.loading');
    if (loading) {
        loading.remove();
    }
    
    // Remove error alerts if they exist
    const errorAlert = feed.querySelector('.alert');
    if (errorAlert) {
        errorAlert.remove();
    }
    
    // Create post element
    const postElement = createPostElement(post);
    
    // Add to top of feed
    if (feed.firstChild) {
        feed.insertBefore(postElement, feed.firstChild);
    } else {
        feed.appendChild(postElement);
    }
}

// Function to create a post element
function createPostElement(post) {
    const postDiv = document.createElement('div');
    postDiv.className = 'post-card';
    postDiv.id = `post-${post.id}`;
    
    postDiv.innerHTML = `
        <div class="post-header">
            <div class="post-avatar">
                ${post.user.avatar}
            </div>
            <div>
                <div class="post-user">${post.user.name}</div>
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
        
        <div class="comments-section" id="comments-${post.id}" style="display: none;">
            <div class="comment-form">
                <input type="text" class="comment-input" placeholder="Write a comment..." id="comment-input-${post.id}">
                <button class="btn btn-sm" style="background: var(--accent-cyan); color: #000;" onclick="addComment(${post.id})">
                    <i class="bi bi-send"></i>
                </button>
            </div>
            <div id="comments-list-${post.id}">
                ${post.comments.map(comment => `
                    <div class="comment">
                        <div class="comment-avatar">
                            ${comment.user.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div class="comment-content">
                            <div class="comment-user">${comment.user.name}</div>
                            <div class="comment-text">${escapeHtml(comment.content)}</div>
                            <div class="comment-time">${formatTime(comment.created_at)}</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
    
    return postDiv;
}

async function loadPosts() {
    const feed = document.getElementById('postsFeed');
    feed.innerHTML = '<div class="loading"><i class="bi bi-arrow-repeat spinner"></i> Loading posts...</div>';

    try {
        console.log('Loading posts from /api/posts with credentials');
        
        const response = await fetch('/api/posts', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('Posts response status:', response.status);
        
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Please log in to view posts (401 Unauthorized)');
            } else if (response.status === 500) {
                const errorText = await response.text();
                console.error('Server 500 error:', errorText);
                throw new Error('Server error. Please try again later.');
            } else {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
        }

        const posts = await response.json();
        console.log('Posts API response:', posts);

        if (!Array.isArray(posts)) {
            console.error('Expected array but got:', typeof posts, posts);
            throw new Error('Invalid response format from server');
        }

        if (posts.length === 0) {
            feed.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>Nothing here yet</h4>
                    <p>Be the first to share something with the community!</p>
                </div>
            `;
            return;
        }

        // Render posts with full data
        feed.innerHTML = posts.map(post => `
            <div class="post-card" id="post-${post.id}">
                <div class="post-header">
                    <div class="post-avatar">
                        ${post.user.avatar}
                    </div>
                    <div>
                        <div class="post-user">${post.user.name} <small style="opacity: 0.7;">@${post.user.username}</small></div>
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
                
                <div class="comments-section" id="comments-${post.id}" style="display: none;">
                    <div class="comment-form">
                        <input type="text" class="comment-input" placeholder="Write a comment..." id="comment-input-${post.id}">
                        <button class="btn btn-sm" style="background: var(--accent-cyan); color: #000;" onclick="addComment(${post.id})">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                    <div id="comments-list-${post.id}">
                        ${post.comments.map(comment => `
                            <div class="comment">
                                <div class="comment-avatar">
                                    ${comment.user.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div class="comment-content">
                                    <div class="comment-user">${comment.user.name}</div>
                                    <div class="comment-text">${escapeHtml(comment.content)}</div>
                                    <div class="comment-time">${formatTime(comment.created_at)}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `).join('');

    } catch (error) {
        console.error('Error loading posts:', error);
        feed.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                ${error.message}
                <br><small>Check console for details.</small>
            </div>
        `;
    }
}

// Enhanced timed alert function
function showTimedAlert(message, type = 'info') {
    // Remove any existing alerts first
    document.querySelectorAll('.timed-alert').forEach(alert => alert.remove());

    const alert = document.createElement('div');
    alert.className = `timed-alert alert alert-${type}`;
    alert.innerHTML = `
        <i class="bi ${getAlertIcon(type)}"></i>
        ${message}
    `;
    
    // Add styles for timed alerts
    alert.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease, slideOutRight 0.3s ease 4.7s forwards;
    `;
    
    document.body.appendChild(alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

function getAlertIcon(type) {
    const icons = {
        'success': 'bi-check-circle-fill',
        'danger': 'bi-exclamation-triangle-fill',
        'warning': 'bi-exclamation-triangle-fill',
        'info': 'bi-info-circle-fill'
    };
    return icons[type] || 'bi-info-circle-fill';
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

// Add CSS animations for alerts
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .timed-alert {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    }
`;
document.head.appendChild(style);

// Like post function with better error handling
async function likePost(postId) {
    try {
        console.log('Liking post:', postId);
        
        const response = await fetch(`/api/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            credentials: 'same-origin'
        });

        console.log('Like response status:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Like error response:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Like response data:', data);
        
        if (data.success) {
            // Update the like count and state in the UI
            const likeButton = document.querySelector(`#post-${postId} .post-action`);
            const likeCount = likeButton.querySelector('span');
            
            likeButton.classList.toggle('liked', data.liked);
            likeButton.innerHTML = `
                <i class="bi ${data.liked ? 'bi-heart-fill' : 'bi-heart'}"></i> 
                <span>${data.likes_count}</span>
            `;
        } else {
            showTimedAlert(data.message || 'Failed to like post. Please try again.', 'danger');
        }
    } catch (error) {
        console.error('Error liking post:', error);
        showTimedAlert('Network error. Please try again.', 'danger');
    }
}

function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-${postId}`);
    if (commentsSection) {
        commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
    }
}

async function addComment(postId) {
    const commentInput = document.getElementById(`comment-input-${postId}`);
    const content = commentInput.value.trim();
    
    if (!content) return;

    try {
        console.log('Adding comment to post:', postId, 'Content:', content);
        
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

        console.log('Comment response status:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Comment error response:', errorText);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Comment response data:', data);
        
        if (data.success) {
            commentInput.value = '';
            // Reload posts to show the new comment
            await loadPosts();
            
            // Keep comments open
            const commentsSection = document.getElementById(`comments-${postId}`);
            if (commentsSection) {
                commentsSection.style.display = 'block';
            }
            
            showTimedAlert('Comment added successfully!', 'success');
        } else {
            showTimedAlert(data.message || 'Failed to add comment. Please try again.', 'danger');
        }
    } catch (error) {
        console.error('Error adding comment:', error);
        showTimedAlert('Network error. Please try again.', 'danger');
    }
}

// Load posts on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, loading posts...');
    loadPosts();
});

// Auto-refresh posts every 30 seconds
setInterval(loadPosts, 30000);
</script>