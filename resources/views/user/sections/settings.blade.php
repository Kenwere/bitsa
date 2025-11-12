<section id="settings-section" class="content-section">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="bi bi-gear"></i> Settings</h2>
        </div>
        
        <div class="row">
            <!-- Account Settings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Account Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Change Password</label>
                            <input type="password" class="form-control" placeholder="Current password" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" placeholder="New password" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" placeholder="Confirm new password" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                        </div>
                        <button class="btn" style="background: var(--accent-cyan); color: #000;">
                            <i class="bi bi-key"></i> Update Password
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Notification Settings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Notifications</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                            <label class="form-check-label" for="pushNotifications">Push Notifications</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="eventReminders">
                            <label class="form-check-label" for="eventReminders">Event Reminders</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="postLikes" checked>
                            <label class="form-check-label" for="postLikes">Post Likes & Comments</label>
                        </div>
                        <button class="btn" style="background: var(--accent-purple); color: #fff;">
                            <i class="bi bi-bell"></i> Save Preferences
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Privacy Settings -->
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Privacy Settings</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Profile Visibility</label>
                            <select class="form-control" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                                <option>Public</option>
                                <option>Members Only</option>
                                <option>Private</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Who can message you</label>
                            <select class="form-control" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                                <option>Everyone</option>
                                <option>Members Only</option>
                                <option>No one</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn" style="background: var(--accent-pink); color: #fff;">
                    <i class="bi bi-shield-check"></i> Update Privacy Settings
                </button>
            </div>
        </div>
    </div>
</section>