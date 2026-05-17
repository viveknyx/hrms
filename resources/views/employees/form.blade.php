<x-app-layout>
    @php
        $editing = $employee->exists;
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-semibold mb-1">{{ $editing ? 'Edit Employee' : 'New Employee' }}</h3>
            <p class="text-muted mb-0">Maintain login, employment, reporting, payroll, and emergency details.</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <form method="post" action="{{ $editing ? route('employees.update', $employee) : route('employees.store') }}">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Account</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Full Name</label>
                        <input name="name" value="{{ old('name', $employee->name) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" {{ $editing ? '' : 'required' }} placeholder="{{ $editing ? 'Leave blank to keep existing' : 'Minimum 8 characters' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Employee Code</label>
                        <input name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-select" required>
                            <option value="">Select role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id', $employee->role_id) == $role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach (['active', 'probation', 'on_leave', 'inactive'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $employee->status ?: 'active') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Color</label>
                        <input type="color" name="avatar_color" value="{{ old('avatar_color', $employee->avatar_color ?: '#2563eb') }}" class="form-control form-control-color">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Employment</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Select department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', $profile->department_id) == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">Select designation</option>
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}" @selected(old('designation_id', $profile->designation_id) == $designation->id)>{{ $designation->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Manager</label>
                        <select name="manager_user_id" class="form-select">
                            <option value="">No manager</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}" @selected(old('manager_user_id', $profile->manager_user_id) == $manager->id)>{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" value="{{ old('joining_date', optional($profile->joining_date)->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Employment Type</label>
                        <select name="employment_type" class="form-select" required>
                            @foreach (['Full-time', 'Part-time', 'Contract', 'Internship'] as $type)
                                <option value="{{ $type }}" @selected(old('employment_type', $profile->employment_type ?: 'Full-time') === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Work Location</label>
                        <input name="work_location" value="{{ old('work_location', $profile->work_location) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Monthly Salary</label>
                        <input type="number" step="0.01" name="salary" value="{{ old('salary', $profile->salary ?: 0) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <input name="gender" value="{{ old('gender', $profile->gender) }}" class="form-control">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Address</label>
                        <input name="address" value="{{ old('address', $profile->address) }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Emergency and Bank Details</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Emergency Contact</label>
                        <input name="emergency_contact_name" value="{{ old('emergency_contact_name', $profile->emergency_contact_name) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Emergency Phone</label>
                        <input name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bank Name</label>
                        <input name="bank_name" value="{{ old('bank_name', $profile->bank_name) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Account Last 4</label>
                        <input name="bank_account_last4" value="{{ old('bank_account_last4', $profile->bank_account_last4) }}" maxlength="4" class="form-control">
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary">{{ $editing ? 'Update Employee' : 'Create Employee' }}</button>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
