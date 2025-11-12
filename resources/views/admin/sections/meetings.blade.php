<section id="meetings-section" class="content-section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="card-title mb-0"><i class="bi bi-camera-video"></i> Meeting Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                <i class="bi bi-plus-circle"></i> Create Meeting
            </button>
        </div>
        <div class="card-body">
            <!-- Active Meetings -->
            <div class="mb-4">
                <h4 class="mb-3">
                    <i class="bi bi-camera-video text-danger"></i> Active Meetings
                    <small class="text-muted" id="activeMeetingsCount"></small>
                </h4>
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
            <div class="mb-4">
                <h4 class="mb-3">
                    <i class="bi bi-calendar text-primary"></i> Scheduled Meetings
                    <small class="text-muted" id="scheduledMeetingsCount"></small>
                </h4>
                <div id="scheduledMeetings">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading scheduled meetings...</p>
                    </div>
                </div>
            </div>

            <!-- All Meetings Table -->
            <div>
                <h4 class="mb-3">
                    <i class="bi bi-list-ul"></i> All Meetings
                </h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Created By</th>
                                <th>Creator Type</th>
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
                                <td>
                                    @if($meeting->created_by_type === 'admin')
                                        {{ $meeting->admin->name ?? 'Admin' }}
                                    @else
                                        {{ $meeting->user->name ?? 'User' }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $meeting->created_by_type === 'admin' ? 'badge-info' : 'badge-success' }}">
                                        {{ ucfirst($meeting->created_by_type) }}
                                    </span>
                                </td>
                                <td>{{ $meeting->scheduled_time->format('M j, Y g:i A') }}</td>
                                <td>{{ $meeting->participants_count }}</td>
                                <td>
                                    <span class="badge {{ $meeting->type === 'public' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($meeting->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($meeting->scheduled_time <= now() && $meeting->is_active)
                                        <span class="badge badge-danger">Live</span>
                                    @elseif($meeting->scheduled_time > now() && $meeting->is_active)
                                        <span class="badge badge-primary">Scheduled</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($meeting->scheduled_time <= now() && $meeting->is_active)
                                            <a href="{{ route('meeting.room', $meeting) }}" class="btn btn-success btn-sm" target="_blank">
                                                <i class="bi bi-camera-video"></i> Join
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.meetings.delete', $meeting) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this meeting?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Create Meeting Modal -->
<div class="modal fade" id="createMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="createMeetingBtn">
                            <i class="bi bi-plus-circle"></i> Create Meeting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.meeting-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.meeting-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.meeting-live {
    border-left: 4px solid var(--accent-danger);
}

.meeting-scheduled {
    border-left: 4px solid var(--accent-primary);
}

.live-badge {
    background: var(--accent-danger);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.scheduled-badge {
    background: var(--accent-primary);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.private-badge {
    background: var(--accent-warning);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Ensure the button is visible */
.btn-primary {
    background: var(--accent-primary) !important;
    border: none !important;
    color: white !important;
}

.btn-primary:hover {
    background: #1d4ed8 !important;
    transform: translateY(-1px);
}
</style>

<script>
// Load meetings for admin
async function loadAdminMeetings() {
    try {
        console.log('Loading admin meetings...');
        
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
        console.log('Admin meetings API response:', data);

        if (data.success) {
            renderAdminMeetings(data.active_meetings, data.scheduled_meetings);
        } else {
            throw new Error(data.message || 'Failed to load meetings');
        }
    } catch (error) {
        console.error('Error loading admin meetings:', error);
        showAlert('Failed to load meetings: ' + error.message, 'danger');
        
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

function renderAdminMeetings(activeMeetings, scheduledMeetings) {
    const activeContainer = document.getElementById('activeMeetings');
    const scheduledContainer = document.getElementById('scheduledMeetings');
    
    // Update counts
    document.getElementById('activeMeetingsCount').textContent = `(${activeMeetings.length})`;
    document.getElementById('scheduledMeetingsCount').textContent = `(${scheduledMeetings.length})`;

    // Render active meetings
    if (activeMeetings.length > 0) {
        activeContainer.innerHTML = activeMeetings.map(meeting => `
            <div class="col-md-6">
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
                        <span class="text-primary">${escapeHtml(meeting.creator_name || (meeting.admin ? meeting.admin.name : meeting.user ? meeting.user.name : 'Host'))}</span>
                        <span class="badge ${meeting.created_by_type === 'admin' ? 'badge-info' : 'badge-success'} ms-1">
                            ${meeting.created_by_type}
                        </span>
                    </p>
                    
                    ${meeting.description ? `<p class="mb-3 small">${escapeHtml(meeting.description)}</p>` : ''}
                    
                    <div class="text-muted mb-2">
                        <i class="bi bi-clock"></i> Started ${formatRelativeTime(meeting.scheduled_time)}
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <i class="bi bi-people"></i>
                            <span>${meeting.participants_count} participants</span>
                        </div>
                        <div>
                            <a href="/meeting/${meeting.id}" class="btn btn-success btn-sm" target="_blank">
                                <i class="bi bi-camera-video"></i> Join
                            </a>
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
                    <h5>No Active Meetings</h5>
                    <p>There are no live meetings at the moment.</p>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
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
                    <span class="text-primary">${escapeHtml(meeting.creator_name || (meeting.admin ? meeting.admin.name : meeting.user ? meeting.user.name : 'Host'))}</span>
                    <span class="badge ${meeting.created_by_type === 'admin' ? 'badge-info' : 'badge-success'} ms-1">
                        ${meeting.created_by_type}
                    </span>
                </p>
                
                ${meeting.description ? `<p class="mb-3 small">${escapeHtml(meeting.description)}</p>` : ''}
                
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted">
                        <i class="bi bi-calendar"></i> ${formatMeetingDate(meeting.scheduled_time)}
                    </div>
                    <div>
                        ${isMeetingReady(meeting.scheduled_time) ? 
                            `<a href="/meeting/${meeting.id}" class="btn btn-primary btn-sm" target="_blank">
                                <i class="bi bi-camera-video"></i> Join
                            </a>` : 
                            `<span class="text-muted">Starts ${formatRelativeTime(meeting.scheduled_time)}</span>`
                        }
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        scheduledContainer.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <h5>No Scheduled Meetings</h5>
                <p>No upcoming meetings are scheduled.</p>
                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createMeetingModal">
                    <i class="bi bi-plus-circle"></i> Schedule a Meeting
                </button>
            </div>
        `;
    }
}

// Create meeting for admin
async function createAdminMeeting() {
    const form = document.getElementById('createMeetingForm');
    const formData = new FormData(form);
    const createBtn = document.getElementById('createMeetingBtn');

    // Validate form
    const title = formData.get('title');
    const date = formData.get('date');
    const time = formData.get('time');

    if (!title || !date || !time) {
        showAlert('Please fill in all required fields', 'danger');
        return;
    }

    // Disable button and show loading
    createBtn.disabled = true;
    createBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Creating...';

    try {
        const response = await fetch('/admin/meetings', {
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
            showAlert('Meeting created successfully!', 'success');
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createMeetingModal'));
            modal.hide();
            
            form.reset();
            await loadAdminMeetings();
            
            // Reload the table data after a short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            const errorMessage = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
            showAlert(errorMessage || 'Failed to create meeting', 'danger');
        }
    } catch (error) {
        console.error('Error creating meeting:', error);
        showAlert('Failed to create meeting: ' + error.message, 'danger');
    } finally {
        // Re-enable button
        createBtn.disabled = false;
        createBtn.innerHTML = '<i class="bi bi-plus-circle"></i> Create Meeting';
    }
}

// Utility functions
function formatMeetingDate(dateString) {
    try {
        const date = new Date(dateString);
        const now = new Date();
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 1);

        let dateStr = date.toLocaleDateString('en-US', { 
            weekday: 'long', 
            month: 'short', 
            day: 'numeric' 
        });
        
        if (date.toDateString() === now.toDateString()) {
            dateStr = 'Today';
        } else if (date.toDateString() === tomorrow.toDateString()) {
            dateStr = 'Tomorrow';
        }

        const timeStr = date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
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
        const diffMs = date - now; // Future dates
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMs < 0) return 'now';
        if (diffMins < 60) return `in ${diffMins}m`;
        if (diffHours < 24) return `in ${diffHours}h`;
        if (diffDays < 7) return `in ${diffDays}d`;
        return formatMeetingDate(dateString);
    } catch (error) {
        console.error('Error formatting relative time:', error);
        return 'soon';
    }
}

function isMeetingReady(scheduledTime) {
    try {
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

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Loading admin meetings...');
    loadAdminMeetings();
    
    // Set minimum date to today
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

    // Handle form submission
    const form = document.getElementById('createMeetingForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            createAdminMeeting();
        });
    }

    // Refresh meetings every 30 seconds
    setInterval(loadAdminMeetings, 30000);
});

// Handle modal show event to reset form
document.getElementById('createMeetingModal').addEventListener('show.bs.modal', function () {
    const form = document.getElementById('createMeetingForm');
    if (form) {
        form.reset();
        
        // Reset to default values
        const dateInput = document.getElementById('meetingDate');
        const timeInput = document.getElementById('meetingTime');
        
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
    }
});
</script>