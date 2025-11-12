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