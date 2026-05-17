<x-app-layout>
    <div class="mb-4">
        <h3 class="fw-semibold mb-1">Leave Management</h3>
        <p class="text-muted mb-0">Submit time off and approve pending leave requests.</p>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Request Leave</h5>
                    <form method="post" action="{{ route('leaves.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Leave Type</label>
                            <select name="leave_type_id" class="form-select" required>
                                <option value="">Select type</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->annual_quota }} days)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Reason</label>
                            <textarea name="reason" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">{{ $canManage ? 'All Leave Requests' : 'My Leave Requests' }}</h5>
                        <form method="get">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                @foreach (['pending', 'approved', 'rejected'] as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    @if ($canManage)
                                        <th class="text-end">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaveRequests as $leave)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $leave->user->name }}</div>
                                            <div class="text-muted fs-2">{{ $leave->user->employeeProfile?->department?->name ?? 'Unassigned' }}</div>
                                        </td>
                                        <td>{{ $leave->type->name }}</td>
                                        <td>{{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}</td>
                                        <td>{{ $leave->total_days }}</td>
                                        <td><span class="badge bg-light-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }} text-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($leave->status) }}</span></td>
                                        @if ($canManage)
                                            <td class="text-end">
                                                @if ($leave->status === 'pending')
                                                    <form action="{{ route('leaves.approve', $leave) }}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="btn btn-sm btn-outline-success">Approve</button>
                                                    </form>
                                                    <form action="{{ route('leaves.reject', $leave) }}" method="post" class="d-inline-flex gap-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input name="rejection_reason" class="form-control form-control-sm" placeholder="Reason" required>
                                                        <button class="btn btn-sm btn-outline-danger">Reject</button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">Reviewed</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-muted">No leave requests found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $leaveRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
