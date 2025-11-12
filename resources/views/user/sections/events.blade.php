<section id="events-section" class="content-section">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Events</h2>
        </div>
        
        <div class="card-body">
            <div id="eventsList">
                <div class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <span class="ms-2">Loading events...</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .event-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.2s ease;
    }

    .event-card:hover {
        border-color: var(--accent-primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .event-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .event-date {
        background: var(--accent-primary);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        min-width: 80px;
    }

    .event-month {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .event-day {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .event-content {
        flex: 1;
    }

    .event-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .event-description {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .event-meta {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .event-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .event-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .btn-attend {
        background: var(--accent-primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-attend:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-interested {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .btn-interested:hover {
        border-color: var(--accent-primary);
        color: var(--accent-primary);
    }

    .attendee-count {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-left: auto;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>

<script>
    async function loadEvents() {
        const eventsList = document.getElementById('eventsList');
        
        try {
            const response = await fetch('/api/events', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const events = await response.json();

            if (events.length === 0) {
                eventsList.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h4>No Upcoming Events</h4>
                        <p>Check back later for scheduled events.</p>
                    </div>
                `;
                return;
            }

            eventsList.innerHTML = events.map(event => `
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-date">
                            <div class="event-month">${new Date(event.event_date).toLocaleString('default', { month: 'short' })}</div>
                            <div class="event-day">${new Date(event.event_date).getDate()}</div>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">${escapeHtml(event.title)}</h3>
                            <p class="event-description">${escapeHtml(event.description)}</p>
                            <div class="event-meta">
                                <div class="event-meta-item">
                                    <i class="bi bi-clock"></i>
                                    <span>${event.formatted_time}</span>
                                </div>
                                <div class="event-meta-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>${escapeHtml(event.location)}</span>
                                </div>
                                ${event.max_attendees ? `
                                    <div class="event-meta-item">
                                        <i class="bi bi-people"></i>
                                        <span>${event.max_attendees} max attendees</span>
                                    </div>
                                ` : ''}
                            </div>
                            <div class="event-actions">
                                <button class="btn-attend" onclick="attendEvent(${event.id})">
                                    <i class="bi bi-check-circle"></i> Attend Event
                                </button>
                                <button class="btn-interested" onclick="markInterested(${event.id})">
                                    <i class="bi bi-star"></i> Interested
                                </button>
                                <span class="attendee-count">
                                    <i class="bi bi-person"></i> 
                                    ${event.attendees_count || 0} attending
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

        } catch (error) {
            console.error('Error loading events:', error);
            eventsList.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    Failed to load events. Please try again later.
                </div>
            `;
        }
    }

    async function attendEvent(eventId) {
        try {
            const response = await fetch(`/api/events/${eventId}/attend`, {
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
                showAlert('You are now attending this event!', 'success');
                await loadEvents();
            } else {
                showAlert(data.message || 'Failed to attend event', 'danger');
            }
        } catch (error) {
            console.error('Error attending event:', error);
            showAlert('Network error. Please try again.', 'danger');
        }
    }

    async function markInterested(eventId) {
        try {
            const response = await fetch(`/api/events/${eventId}/interested`, {
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
                showAlert('Marked as interested!', 'success');
            } else {
                showAlert(data.message || 'Failed to mark interest', 'danger');
            }
        } catch (error) {
            console.error('Error marking interest:', error);
            showAlert('Network error. Please try again.', 'danger');
        }
    }

    function escapeHtml(unsafe) {
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
        
        document.querySelector('.main-content').insertBefore(alert, document.querySelector('.main-content').firstChild);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    // Load events when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadEvents();
    });
</script>