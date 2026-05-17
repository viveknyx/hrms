<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card bg-light-primary border-0">
                <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h3 class="fw-semibold mb-1">HRMS Dashboard</h3>
                        <p class="text-muted mb-0">People, time off, attendance, and payroll at a glance.</p>
                    </div>
                    @if (auth()->user()->hasPermission('employees.manage'))
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            <i class="ti ti-user-plus me-1"></i> Add Employee
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ([
            ['label' => 'Active Employees', 'value' => $stats['employees'], 'icon' => 'ti ti-users', 'class' => 'primary'],
            ['label' => 'Departments', 'value' => $stats['departments'], 'icon' => 'ti ti-building', 'class' => 'success'],
            ['label' => 'Present Today', 'value' => $stats['presentToday'], 'icon' => 'ti ti-clock-check', 'class' => 'info'],
            ['label' => 'Pending Leave', 'value' => $stats['pendingLeaves'], 'icon' => 'ti ti-calendar-time', 'class' => 'warning'],
        ] as $stat)
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <span class="round-48 rounded-circle d-flex align-items-center justify-content-center bg-light-{{ $stat['class'] }} text-{{ $stat['class'] }}">
                                <i class="{{ $stat['icon'] }} fs-6"></i>
                            </span>
                            <div>
                                <p class="text-muted mb-1">{{ $stat['label'] }}</p>
                                <h4 class="mb-0 fw-semibold">{{ $stat['value'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">Recent Employees</h5>
                        <a href="{{ route('employees.index') }}" class="text-primary">View all</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentEmployees as $employee)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $employee->name }}</div>
                                            <div class="text-muted fs-2">{{ $employee->employee_code }} · {{ $employee->email }}</div>
                                        </td>
                                        <td>{{ $employee->employeeProfile?->department?->name ?? 'Unassigned' }}</td>
                                        <td>{{ $employee->role?->name ?? 'No role' }}</td>
                                        <td><span class="badge bg-light-success text-success">{{ ucfirst($employee->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">No employees yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Announcements</h5>
                    @forelse ($announcements as $announcement)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="fw-semibold">{{ $announcement->title }}</div>
                            <div class="text-muted fs-2">{{ $announcement->published_at?->format('d M Y') }} by {{ $announcement->publisher?->name ?? 'HR' }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No announcements published.</p>
                    @endforelse
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Pending Leave</h5>
                    @forelse ($pendingLeaves as $leave)
                        <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                            <div>
                                <div class="fw-semibold">{{ $leave->user->name }}</div>
                                <div class="text-muted fs-2">{{ $leave->type->name }} · {{ $leave->total_days }} days</div>
                            </div>
                            <span class="badge bg-light-warning text-warning">Pending</span>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No pending leave requests.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
