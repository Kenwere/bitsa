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
                            <button type="submit" class="btn" style="background: var(--accent-cyan); color: #000;" id="updateProfileBtn">
                                <i class="bi bi-check-circle"></i> Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .user-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto;
        color: #000;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--accent-cyan);
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    /* Profile input styling that works with both themes */
    .profile-input {
        background: rgba(0,0,0,0.3) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        color: var(--text-primary) !important;
        transition: all 0.3s ease;
    }

    .profile-input:focus {
        border-color: var(--accent-cyan) !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 255, 245, 0.25) !important;
        background: rgba(0,0,0,0.4) !important;
    }

    .profile-input:disabled {
        background: rgba(0,0,0,0.2) !important;
        color: var(--text-muted) !important;
        opacity: 0.7;
    }

    /* Ensure text colors work in both themes */
    [data-theme="dark"] .text-muted {
        color: #adb5bd !important;
    }

    [data-theme="light"] .text-muted {
        color: #6c757d !important;
    }

    [data-theme="dark"] .form-label {
        color: #ffffff !important;
    }

    [data-theme="light"] .form-label {
        color: #000000 !important;
    }

    [data-theme="dark"] .card {
        background: var(--card-bg);
        color: var(--text-primary);
    }

    [data-theme="light"] .card {
        background: var(--card-bg);
        color: var(--text-primary);
    }
</style>

<script>
    // Load profile data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadProfileData();
        loadProfileStats();
    });

    // Load user profile data
    async function loadProfileData() {
        try {
            const response = await fetch('/api/profile', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                populateProfileForm(data.user);
            } else {
                throw new Error(data.message || 'Failed to load profile');
            }
        } catch (error) {
            console.error('Error loading profile:', error);
            showTimedAlert('Failed to load profile data: ' + error.message, 'danger');
        }
    }

    // Load profile statistics
    async function loadProfileStats() {
        try {
            const response = await fetch('/api/profile/stats', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                updateProfileStats(data.stats);
            }
        } catch (error) {
            console.error('Error loading profile stats:', error);
            // Don't show alert for stats as they're less critical
        }
    }

    // Populate the profile form with user data
    function populateProfileForm(user) {
        // Update profile header
        document.getElementById('profileName').textContent = user.name;
        document.getElementById('profileUsername').textContent = '@' + user.username;
        document.getElementById('profileEmail').textContent = user.email;

        // Update form fields
        document.querySelector('input[name="name"]').value = user.name;
        document.querySelector('input[name="username"]').value = user.username;
        document.querySelector('textarea[name="bio"]').value = user.bio || '';
        
        // Convert skills array to comma-separated string
        if (user.skills && Array.isArray(user.skills)) {
            document.querySelector('input[name="skills"]').value = user.skills.join(', ');
        } else if (user.skills) {
            document.querySelector('input[name="skills"]').value = user.skills;
        }

        // Update posts count
        document.getElementById('postsCount').textContent = user.posts_count || 0;
    }

    // Update profile statistics
    function updateProfileStats(stats) {
        document.getElementById('postsCount').textContent = stats.posts_count || 0;
        document.getElementById('followingCount').textContent = stats.following_count || 0;
        document.getElementById('followersCount').textContent = stats.followers_count || 0;
    }

    // Handle form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    // Update profile function
    async function updateProfile() {
        const form = document.getElementById('profileForm');
        const formData = new FormData(form);
        const updateBtn = document.getElementById('updateProfileBtn');

        // Disable button and show loading
        updateBtn.disabled = true;
        updateBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Updating...';

        try {
            const response = await fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    username: formData.get('username'),
                    bio: formData.get('bio'),
                    skills: formData.get('skills')
                }),
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                showTimedAlert('Profile updated successfully!', 'success');
                // Update the displayed profile information
                populateProfileForm(data.user);
            } else {
                const errorMessage = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                showTimedAlert(errorMessage || 'Failed to update profile', 'danger');
            }
        } catch (error) {
            console.error('Error updating profile:', error);
            showTimedAlert('Failed to update profile: ' + error.message, 'danger');
        } finally {
            // Re-enable button
            updateBtn.disabled = false;
            updateBtn.innerHTML = '<i class="bi bi-check-circle"></i> Update Profile';
        }
    }

    // Add input validation
    document.querySelectorAll('.profile-input').forEach(input => {
        input.addEventListener('input', function() {
            // Remove any existing validation styles
            this.classList.remove('is-invalid');
            
            // Basic validation
            if (this.name === 'username') {
                const username = this.value.trim();
                if (username && !/^[a-zA-Z0-9_]+$/.test(username)) {
                    this.classList.add('is-invalid');
                    showTimedAlert('Username can only contain letters, numbers, and underscores', 'warning');
                }
            }
        });
    });

    // Add real-time username availability check
    let usernameTimeout;
    document.querySelector('input[name="username"]').addEventListener('input', function() {
        clearTimeout(usernameTimeout);
        const username = this.value.trim();
        
        if (username && username !== '{{ Auth::user()->username }}') {
            usernameTimeout = setTimeout(() => {
                checkUsernameAvailability(username);
            }, 500);
        }
    });

    async function checkUsernameAvailability(username) {
        try {
            // You can implement this later with an API endpoint
            // For now, we'll just do client-side validation
            if (username.length < 3) {
                showTimedAlert('Username must be at least 3 characters long', 'warning');
                return false;
            }
            return true;
        } catch (error) {
            console.error('Error checking username:', error);
        }
    }
</script>