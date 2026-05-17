<x-app-layout>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-semibold mb-1">Employees</h3>
            <p class="text-muted mb-0">Central employee records, roles, departments, and employment status.</p>
        </div>
        @if (auth()->user()->hasPermission('employees.manage'))
            <a href="{{ route('employees.create') }}" class="btn btn-primary">
                <i class="ti ti-user-plus me-1"></i> New Employee
            </a>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="row g-3 mb-4">
                <div class="col-md-5">
                    <input name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, email, or employee code">
                </div>
                <div class="col-md-3">
                    <select name="department" class="form-select">
                        <option value="">All departments</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('department') == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach (['active', 'probation', 'on_leave', 'inactive'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Status</th>
                            @if (auth()->user()->hasPermission('employees.manage'))
                                <th class="text-end">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                            style="width:42px;height:42px;background:{{ $employee->avatar_color }}">
                                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $employee->name }}</div>
                                            <div class="text-muted fs-2">{{ $employee->employee_code }} · {{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $employee->employeeProfile?->department?->name ?? 'Unassigned' }}</td>
                                <td>{{ $employee->employeeProfile?->designation?->title ?? 'Unassigned' }}</td>
                                <td>{{ $employee->role?->name ?? 'No role' }}</td>
                                <td>{{ $employee->employeeProfile?->joining_date?->format('d M Y') ?? '-' }}</td>
                                <td><span class="badge bg-light-{{ $employee->status === 'active' ? 'success' : 'warning' }} text-{{ $employee->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst(str_replace('_', ' ', $employee->status)) }}</span></td>
                                @if (auth()->user()->hasPermission('employees.manage'))
                                    <td class="text-end">
                                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('employees.status', $employee) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-secondary">{{ $employee->status === 'active' ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-muted">No employees found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $employees->links() }}
        </div>
    </div>
</x-app-layout>
