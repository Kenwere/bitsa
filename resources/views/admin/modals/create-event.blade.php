<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.events.create') }}" method="POST" id="createEventForm">
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

<script>
document.getElementById('createEventForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route('admin.events.create') }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createEventModal'));
            modal.hide();
            
            // Show success message
            showAlert('Event created successfully!', 'success');
            
            // Reload page to show new event
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showAlert(data.message || 'Failed to create event', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Failed to create event', 'danger');
    });
});

// Auto-set event date to tomorrow
document.addEventListener('DOMContentLoaded', function() {
    const eventDateInput = document.querySelector('input[name="event_date"]');
    if (eventDateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        eventDateInput.min = tomorrow.toISOString().split('T')[0];
        eventDateInput.value = tomorrow.toISOString().split('T')[0];
    }
});
</script>