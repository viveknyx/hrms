<x-app-layout>
    <div class="mb-4">
        <h3 class="fw-semibold mb-1">Announcements</h3>
        <p class="text-muted mb-0">Company updates, HR notices, and department-specific communication.</p>
    </div>

    <div class="row">
        @if (auth()->user()->hasPermission('announcements.manage'))
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Publish Announcement</h5>
                        <form method="post" action="{{ route('announcements.store') }}" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Title</label>
                                <input name="title" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Audience</label>
                                <select name="audience" class="form-select">
                                    <option value="all">All employees</option>
                                    <option value="department">Department</option>
                                    <option value="managers">Managers</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select">
                                    <option value="">Only when audience is department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Body</label>
                                <textarea name="body" rows="5" class="form-control" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Save Announcement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="{{ auth()->user()->hasPermission('announcements.manage') ? 'col-lg-8' : 'col-lg-12' }}">
            @forelse ($announcements as $announcement)
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <h5 class="fw-semibold mb-1">{{ $announcement->title }}</h5>
                                <p class="text-muted mb-3">
                                    {{ ucfirst($announcement->audience) }}
                                    @if ($announcement->department)
                                        · {{ $announcement->department->name }}
                                    @endif
                                    · {{ $announcement->published_at?->format('d M Y h:i A') ?? 'Draft' }}
                                </p>
                            </div>
                            <span class="badge bg-light-primary text-primary align-self-start">{{ ucfirst($announcement->status) }}</span>
                        </div>
                        <p class="mb-0">{!! nl2br(e($announcement->body)) !!}</p>
                    </div>
                </div>
            @empty
                <div class="card"><div class="card-body text-muted">No announcements found.</div></div>
            @endforelse
            {{ $announcements->links() }}
        </div>
    </div>
</x-app-layout>
