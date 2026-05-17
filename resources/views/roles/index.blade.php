<x-app-layout>
    <div class="mb-4">
        <h3 class="fw-semibold mb-1">Roles and Permissions</h3>
        <p class="text-muted mb-0">Readable permission slugs replace the old two-letter role codes.</p>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Create Role</h5>
                    <form method="post" action="{{ route('roles.store') }}" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="status" value="1">
                        <div class="col-12">
                            <label class="form-label">Permissions</label>
                            @foreach ($permissionGroups as $group => $permissions)
                                <div class="border rounded p-3 mb-2">
                                    <div class="fw-semibold mb-2">{{ $group }}</div>
                                    @foreach ($permissions as $key => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}" id="new-{{ $key }}">
                                            <label class="form-check-label" for="new-{{ $key }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary">Create Role</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            @foreach ($roles as $role)
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('roles.update', $role) }}">
                            @csrf
                            @method('PUT')
                            <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1">{{ $role->name }}</h5>
                                    <p class="text-muted mb-0">{{ $role->description }}</p>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <input name="name" value="{{ $role->name }}" class="form-control form-control-sm" style="width:180px">
                                    <select name="status" class="form-select form-select-sm" style="width:120px">
                                        <option value="1" @selected($role->status)>Active</option>
                                        <option value="0" @selected(! $role->status)>Inactive</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </div>
                            </div>
                            <textarea name="description" class="form-control mb-3" rows="2">{{ $role->description }}</textarea>
                            <div class="row">
                                @foreach ($permissionGroups as $group => $permissions)
                                    <div class="col-md-6">
                                        <div class="border rounded p-3 mb-3 h-100">
                                            <div class="fw-semibold mb-2">{{ $group }}</div>
                                            @foreach ($permissions as $key => $label)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}"
                                                        id="role-{{ $role->id }}-{{ $key }}"
                                                        @checked(in_array($key, $role->permissions ?? [], true) || $role->slug === 'super-admin')>
                                                    <label class="form-check-label" for="role-{{ $role->id }}-{{ $key }}">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
