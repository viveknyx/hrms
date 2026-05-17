<x-app-layout>
    <div class="mb-4">
        <h3 class="fw-semibold mb-1">Departments and Designations</h3>
        <p class="text-muted mb-0">Keep your organization structure clean and reusable across employee records.</p>
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Add Department</h5>
                    <form method="post" action="{{ route('departments.store') }}" class="row g-3">
                        @csrf
                        <div class="col-md-7">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Code</label>
                            <input name="code" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Manager</label>
                            <select name="manager_user_id" class="form-select">
                                <option value="">No manager</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="status" value="1">
                        <div class="col-12">
                            <button class="btn btn-primary">Save Department</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Add Designation</h5>
                    <form method="post" action="{{ route('designations.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">Select department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Title</label>
                            <input name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Grade</label>
                            <input name="grade" class="form-control">
                        </div>
                        <input type="hidden" name="status" value="1">
                        <div class="col-12">
                            <button class="btn btn-outline-primary">Save Designation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Departments</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Designations</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $department)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $department->name }}</div>
                                            <div class="text-muted fs-2">{{ $department->description }}</div>
                                        </td>
                                        <td>{{ $department->code }}</td>
                                        <td>{{ $department->designations->count() }}</td>
                                        <td><span class="badge bg-light-success text-success">{{ $department->status ? 'Active' : 'Inactive' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Designations</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Department</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($designations as $designation)
                                    <tr>
                                        <td>{{ $designation->title }}</td>
                                        <td>{{ $designation->department?->name }}</td>
                                        <td>{{ $designation->grade ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
