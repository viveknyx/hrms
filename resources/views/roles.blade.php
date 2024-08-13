<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Roles</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('users') }}">Users</a></li>
                            <li class="breadcrumb-item" aria-current="page">Roles</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('dist/images/breadcrumb/ChatBc.png') }}" alt=""
                            class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div>
                                <a style="background: #5d87ff;" href="{{ url('add-new-role') }}"
                                    class="justify-content-center btn mb-3 btn-primary align-items-center">
                                    <i class="ti ti-plus fs-4 me-2"></i>
                                    Add New Role
                                </a>
                            </div>
                            <table id="zero_config" class="table border table-bordered text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>S.No</th>
                                        <th>Role Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @php
                                        $roles = DB::table('roles')->get();
                                        $count = 1;
                                    @endphp

                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $role->name }}</td>

                                            @if ($role->status == 1)
                                                <td><span
                                                        class="badge bg-light-success text-success fw-semibold fs-2 cursor-pointer"><a
                                                            href="{{ route('change.status', ['type' => 4, 'value' => 0, 'id' => $role->id]) }}"
                                                            title="Click to Deactivate">Active</a></span>
                                                </td>
                                            @else
                                                <td><span
                                                        class="badge bg-light-warning text-warning fw-semibold fs-2 cursor-pointer"><a
                                                            href="{{ route('change.status', ['type' => 4, 'value' => 1, 'id' => $role->id]) }}"
                                                            title="Click to Activate">Inactive</a></span>
                                                </td>
                                            @endif

                                            <td>
                                                <div class="btn-group mb-2">
                                                    <button type="button" style="background: #2a3547;"
                                                        class="btn btn-dark dropdown-toggle text-white"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ti ti-settings fs-4"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="{{ url('edit-role/' . $role->id) }}"
                                                                style="cursor: pointer;">Edit</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
