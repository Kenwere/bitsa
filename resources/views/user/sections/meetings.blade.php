<section id="meetings-section" class="content-section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0"><i class="bi bi-camera-video"></i> Live Meetings</h2>
            <button class="btn" style="background: var(--accent-pink); color: #fff;" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
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
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading active meetings...</p>
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
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading scheduled meetings...</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Create Meeting Modal -->
<div class="modal fade" id="createMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); color: var(--text-primary);">
            <div class="modal-header">
                <h5 class="modal-title">Create New Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createMeetingForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Meeting Title *</label>
                        <input type="text" class="form-control" name="title" required 
                               placeholder="Enter meeting title"
                               style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" 
                                  placeholder="Describe the purpose of this meeting"
                                  style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" name="date" required 
                                       style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Time *</label>
                                <input type="time" class="form-control" name="time" required 
                                       style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Type *</label>
                        <select class="form-control" name="type" 
                                style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: var(--text-primary);">
                            <option value="public">Public - Anyone can join</option>
                            <option value="private">Private - Only invited users can join</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" style="background: var(--accent-pink); color: #fff;" onclick="createMeeting()" id="createMeetingBtn">
                    <i class="bi bi-plus-circle"></i> Create Meeting
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .meeting-card {
        background: rgba(255,255,255,0.05);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.3s ease;
    }

    .meeting-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .meeting-live {
        border-left: 4px solid var(--accent-pink);
    }

    .meeting-scheduled {
        border-left: 4px solid var(--accent-cyan);
    }

    .live-badge {
        background: var(--accent-pink);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .scheduled-badge {
        background: var(--accent-cyan);
        color: #000;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .private-badge {
        background: var(--accent-purple);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .participants {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .participant-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        color: #000;
        flex-shrink: 0;
    }

    .participants-list {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }

    .host-badge {
        background: var(--accent-pink);
        color: white;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        font-size: 0.7rem;
        position: absolute;
        top: -5px;
        right: -5px;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
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

    .meeting-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .meeting-host {
        color: var(--accent-pink);
        font-weight: 600;
    }
</style>

<script>
    // Load meetings from API
  
    async function loadMeetings() {
        try {
            console.log('Loading meetings from API...');
            
            const response = await fetch('/api/meetings', {
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
            console.log('Meetings API response:', data);

            if (data.success) {
                renderMeetings(data.active_meetings, data.scheduled_meetings);
            } else {
                throw new Error(data.message || 'Failed to load meetings');
            }
        } catch (error) {
            console.error('Error loading meetings:', error);
            showTimedAlert('Failed to load meetings: ' + error.message, 'danger');
            
            // Show error state
            document.getElementById('activeMeetings').innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Failed to load meetings. Please try again.
                    </div>
                </div>
            `;
            
            document.getElementById('scheduledMeetings').innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    Failed to load meetings. Please try again.
                </div>
            `;
        }
    }

    function renderMeetings(activeMeetings, scheduledMeetings) {
        const activeContainer = document.getElementById('activeMeetings');
        const scheduledContainer = document.getElementById('scheduledMeetings');
        
        // Update counts
        document.getElementById('activeMeetingsCount').textContent = `(${activeMeetings.length})`;
        document.getElementById('scheduledMeetingsCount').textContent = `(${scheduledMeetings.length})`;

        // Render active meetings
        if (activeMeetings.length > 0) {
            activeContainer.innerHTML = activeMeetings.map(meeting => `
                <div class="col-md-6 col-lg-4">
                    <div class="meeting-card meeting-live">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-1">${escapeHtml(meeting.title)}</h5>
                            <div>
                                <span class="live-badge">LIVE</span>
                                ${meeting.type === 'private' ? '<span class="private-badge ms-1"><i class="bi bi-lock"></i></span>' : ''}
                            </div>
                        </div>
                        
                        <p class="text-muted mb-2">
                            <i class="bi bi-person"></i> 
                            <span class="meeting-host">${escapeHtml(meeting.user.name)}</span>
                        </p>
                        
                        ${meeting.description ? `<p class="mb-3 small">${escapeHtml(meeting.description)}</p>` : ''}
                        
                        <div class="text-muted mb-2">
                            <i class="bi bi-clock"></i> Started ${formatRelativeTime(meeting.scheduled_time)}
                        </div>
                        
                        ${meeting.active_participants && meeting.active_participants.length > 0 ? `
                            <div class="mb-3">
                                <small class="text-muted">Currently in meeting:</small>
                                <div class="participants-list">
                                    ${meeting.active_participants.slice(0, 6).map(participant => `
                                        <div class="avatar-container" title="${escapeHtml(participant.user.name)} ${participant.is_host ? '(Host)' : ''}">
                                            <div class="participant-avatar">
                                                ${participant.user.name.substring(0, 2).toUpperCase()}
                                            </div>
                                            ${participant.is_host ? '<span class="host-badge">H</span>' : ''}
                                        </div>
                                    `).join('')}
                                    ${meeting.active_participants.length > 6 ? 
                                        `<div class="participant-avatar" style="background: var(--accent-cyan);">+${meeting.active_participants.length - 6}</div>` : 
                                        ''
                                    }
                                </div>
                            </div>
                        ` : ''}
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="participants">
                                <i class="bi bi-people"></i>
                                <span>${meeting.participants_count} participants</span>
                            </div>
                            <div class="meeting-actions">
                                ${meeting.user_id === {{ Auth::id() }} ? `
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteMeeting(${meeting.id})" title="Delete Meeting">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                ` : ''}
                                <button class="btn btn-sm" style="background: var(--accent-cyan); color: #000;" onclick="joinMeeting(${meeting.id})">
                                    <i class="bi bi-camera-video"></i> Join
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            activeContainer.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-camera-video-off"></i>
                        <h4>No Active Meetings</h4>
                        <p>There are no live meetings at the moment.</p>
                        <button class="btn mt-2" style="background: var(--accent-pink); color: #fff;" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                            <i class="bi bi-plus-circle"></i> Start a Meeting
                        </button>
                    </div>
                </div>
            `;
        }

        // Render scheduled meetings
        if (scheduledMeetings.length > 0) {
            scheduledContainer.innerHTML = scheduledMeetings.map(meeting => `
                <div class="meeting-card meeting-scheduled">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-1">${escapeHtml(meeting.title)}</h5>
                        <div>
                            <span class="scheduled-badge">SCHEDULED</span>
                            ${meeting.type === 'private' ? '<span class="private-badge ms-1"><i class="bi bi-lock"></i></span>' : ''}
                        </div>
                    </div>
                    
                    <p class="text-muted mb-2">
                        <i class="bi bi-person"></i> 
                        <span class="meeting-host">${escapeHtml(meeting.user.name)}</span>
                    </p>
                    
                    ${meeting.description ? `<p class="mb-3 small">${escapeHtml(meeting.description)}</p>` : ''}
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <span class="text-muted">
                                <i class="bi bi-calendar"></i> ${formatMeetingDate(meeting.scheduled_time)}
                            </span>
                            <span class="ms-3 text-muted">
                                <i class="bi bi-people"></i> ${meeting.participants ? meeting.participants.length : 0} attending
                            </span>
                        </div>
                        <div class="meeting-actions">
                            ${meeting.user_id === {{ Auth::id() }} ? `
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteMeeting(${meeting.id})" title="Delete Meeting">
                                    <i class="bi bi-trash"></i>
                                </button>
                            ` : ''}
                            ${meeting.participants && meeting.participants.some(p => p.user_id === {{ Auth::id() }}) ? 
                                `<span class="badge bg-success me-2">RSVP'd</span>` : 
                                `<button class="btn btn-sm me-2" style="background: var(--accent-purple); color: #fff;" onclick="rsvpMeeting(${meeting.id})">
                                    <i class="bi bi-calendar-plus"></i> RSVP
                                </button>`
                            }
                            <button class="btn btn-sm" style="background: var(--accent-cyan); color: #000;" onclick="joinMeeting(${meeting.id})" ${isMeetingReady(meeting.scheduled_time) ? '' : 'disabled'}>
                                <i class="bi bi-camera-video"></i> Join
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            scheduledContainer.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h4>No Scheduled Meetings</h4>
                    <p>No upcoming meetings are scheduled.</p>
                    <button class="btn mt-2" style="background: var(--accent-pink); color: #fff;" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                        <i class="bi bi-plus-circle"></i> Schedule a Meeting
                    </button>
                </div>
            `;
        }
    }

    // Create new meeting
    async function createMeeting() {
        const form = document.getElementById('createMeetingForm');
        const formData = new FormData(form);
        const createBtn = document.getElementById('createMeetingBtn');

        // Validate form
        const title = formData.get('title');
        const date = formData.get('date');
        const time = formData.get('time');

        if (!title || !date || !time) {
            showTimedAlert('Please fill in all required fields', 'danger');
            return;
        }

        // Disable button and show loading
        createBtn.disabled = true;
        createBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Creating...';

        try {
            const response = await fetch('/api/meetings', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();
            console.log('Create meeting response:', data);

            if (data.success) {
                showTimedAlert('Meeting created successfully!', 'success');
                
                // Use vanilla JS to hide modal instead of jQuery
                const modal = bootstrap.Modal.getInstance(document.getElementById('createMeetingModal'));
                modal.hide();
                
                form.reset();
                await loadMeetings();
            } else {
                const errorMessage = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                showTimedAlert(errorMessage || 'Failed to create meeting', 'danger');
            }
        } catch (error) {
            console.error('Error creating meeting:', error);
            showTimedAlert('Failed to create meeting: ' + error.message, 'danger');
        } finally {
            // Re-enable button
            createBtn.disabled = false;
            createBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Create Meeting';
        }
    }

    // Join meeting
    async function joinMeeting(meetingId) {
        try {
            console.log('Joining meeting:', meetingId);
            
            const response = await fetch(`/api/meetings/${meetingId}/join`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                showTimedAlert('Joining meeting...', 'success');
                // Redirect to meeting room after a short delay
                setTimeout(() => {
                    window.location.href = `/meeting/${meetingId}`;
                }, 1000);
            } else {
                showTimedAlert(data.message || 'Failed to join meeting', 'danger');
            }
        } catch (error) {
            console.error('Error joining meeting:', error);
            showTimedAlert('Failed to join meeting: ' + error.message, 'danger');
        }
    }

    // RSVP to meeting
    async function rsvpMeeting(meetingId) {
        try {
            const response = await fetch(`/api/meetings/${meetingId}/rsvp`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                showTimedAlert('RSVP successful! You are now attending this meeting.', 'success');
                await loadMeetings();
            } else {
                showTimedAlert(data.message || 'Failed to RSVP', 'danger');
            }
        } catch (error) {
            console.error('Error RSVPing to meeting:', error);
            showTimedAlert('Failed to RSVP: ' + error.message, 'danger');
        }
    }

    // Delete meeting
    async function deleteMeeting(meetingId) {
        if (!confirm('Are you sure you want to delete this meeting? This action cannot be undone.')) {
            return;
        }

        try {
            const response = await fetch(`/api/meetings/${meetingId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                showTimedAlert('Meeting deleted successfully!', 'success');
                await loadMeetings();
            } else {
                showTimedAlert(data.message || 'Failed to delete meeting', 'danger');
            }
        } catch (error) {
            console.error('Error deleting meeting:', error);
            showTimedAlert('Failed to delete meeting: ' + error.message, 'danger');
        }
    }

    // Utility functions - Show exact time without conversion
    function formatMeetingDate(dateString) {
        try {
            const date = new Date(dateString);
            
            // Use the exact date and time without any conversion
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);

            let dateStr = date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                month: 'short', 
                day: 'numeric' 
            });
            
            // Add relative time for today/tomorrow
            if (date.toDateString() === now.toDateString()) {
                dateStr = 'Today';
            } else if (date.toDateString() === tomorrow.toDateString()) {
                dateStr = 'Tomorrow';
            }

            // Get the exact time as stored (no conversion)
            const timeStr = date.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false 
            });
            
            return dateStr + ' at ' + timeStr;

        } catch (error) {
            console.error('Error formatting date:', error);
            return 'Date not set';
        }
    }

    function formatRelativeTime(dateString) {
        try {
            const date = new Date(dateString);
            const now = new Date();
            
            // Use exact times without conversion for comparison
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);

            if (diffMins < 1) return 'just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            if (diffHours < 24) return `${diffHours}h ago`;
            return formatMeetingDate(dateString);
        } catch (error) {
            console.error('Error formatting relative time:', error);
            return 'recently';
        }
    }

    function isMeetingReady(scheduledTime) {
        try {
            // Compare exact times without conversion
            const scheduledDate = new Date(scheduledTime);
            const now = new Date();
            
            return scheduledDate <= now;
        } catch (error) {
            console.error('Error checking meeting readiness:', error);
            return false;
        }
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Load meetings when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing meetings...');
        loadMeetings();
        
        // Set minimum date to today
        const dateInput = document.querySelector('input[name="date"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
            dateInput.value = today; // Default to today
        }
        
        // Set default time to next hour
        const timeInput = document.querySelector('input[name="time"]');
        if (timeInput) {
            const nextHour = new Date();
            nextHour.setHours(nextHour.getHours() + 1);
            nextHour.setMinutes(0);
            timeInput.value = nextHour.toTimeString().substring(0, 5);
        }

        // Refresh meetings every 30 seconds
        setInterval(loadMeetings, 30000);
    });

    // Handle modal show event to reset form
    document.getElementById('createMeetingModal').addEventListener('show.bs.modal', function () {
        const form = document.getElementById('createMeetingForm');
        form.reset();
        
        // Reset to default values
        const dateInput = document.querySelector('input[name="date"]');
        const timeInput = document.querySelector('input[name="time"]');
        
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
        }
        
        if (timeInput) {
            const nextHour = new Date();
            nextHour.setHours(nextHour.getHours() + 1);
            nextHour.setMinutes(0);
            timeInput.value = nextHour.toTimeString().substring(0, 5);
        }
    });
</script>