<x-app-layout>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Attendance</h3>
            <p class="text-muted mb-0">Daily clock-in/out records and attendance adjustments.</p>
        </div>
        <div>
            @if ($todayRecord && $todayRecord->clock_in_at && ! $todayRecord->clock_out_at)
                <form action="{{ route('attendance.clock-out') }}" method="post">
                    @csrf
                    <button class="btn btn-primary"><i class="ti ti-clock-off me-1"></i> Clock Out</button>
                </form>
            @else
                <form action="{{ route('attendance.clock-in') }}" method="post">
                    @csrf
                    <button class="btn btn-primary"><i class="ti ti-clock-play me-1"></i> Clock In</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="row g-3 mb-4">
                @if ($canManage)
                    <div class="col-md-3">
                        <select name="user_id" class="form-select">
                            <option value="">All employees</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(request('user_id') == $employee->id)>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td>{{ $record->work_date->format('d M Y') }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $record->user->name }}</div>
                                    <div class="text-muted fs-2">{{ $record->user->employeeProfile?->department?->name ?? 'Unassigned' }}</div>
                                </td>
                                <td>{{ $record->clock_in_at?->format('h:i A') ?? '-' }}</td>
                                <td>{{ $record->clock_out_at?->format('h:i A') ?? '-' }}</td>
                                <td><span class="badge bg-light-primary text-primary">{{ ucfirst(str_replace('_', ' ', $record->status)) }}</span></td>
                                <td>
                                    @if ($canManage)
                                        <form action="{{ route('attendance.update', $record) }}" method="post" class="d-flex gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm" style="width:130px">
                                                @foreach (['present', 'late', 'half_day', 'remote', 'absent', 'leave'] as $status)
                                                    <option value="{{ $status }}" @selected($record->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                                @endforeach
                                            </select>
                                            <input name="notes" value="{{ $record->notes }}" class="form-control form-control-sm" placeholder="Notes">
                                            <button class="btn btn-sm btn-outline-primary">Save</button>
                                        </form>
                                    @else
                                        {{ $record->notes ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-muted">No attendance records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $records->links() }}
        </div>
    </div>
</x-app-layout>
