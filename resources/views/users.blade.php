<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Users</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Users</li>
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
        <!-- basic table -->
        <div class="row">
            <div class="col-12">
                <!-- ---------------------
                        start Zero Configuration
                    ---------------- -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div>
                                <a style="background: #5d87ff;" href="{{ url('add-new-user') }}"
                                    class="justify-content-center btn mb-3 btn-primary align-items-center">
                                    <i class="ti ti-plus fs-4 me-2"></i>
                                    Add New User
                                </a>
                                <a style="background: #fa896b;" href="{{ url('roles') }}"
                                    class="justify-content-center btn mb-3 btn-danger align-items-center">
                                    <i class="ti ti-badge fs-4 me-2"></i>
                                    Roles
                                </a>
                            </div>
                            <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @php
                                        $users = DB::table('users')->get();
                                        $count = 1;
                                    @endphp

                                    @foreach ($users as $user)
                                        <!-- start row -->
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $user->name }}</td>

                                            <td>{{ $user->email }}</td>

                                            <td>{{ DB::table('roles')->where('id', $user->role)->pluck('name')->first() }}
                                            </td>

                                            @if ($user->status == 1)
                                                <td><span
                                                        class="badge bg-light-success text-success fw-semibold fs-2 cursor-pointer"><a
                                                            href="{{ route('change.status', ['type' => 5, 'value' => 0, 'id' => $user->id]) }}"
                                                            title="Click to Deactivate">Active</a></span>
                                                </td>
                                            @else
                                                <td><span
                                                        class="badge bg-light-warning text-warning fw-semibold fs-2 cursor-pointer"><a
                                                            href="{{ route('change.status', ['type' => 5, 'value' => 1, 'id' => $user->id]) }}"
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
                                                    <ul class="dropdown-menu cursor-pointer">
                                                        <li><a class="dropdown-item"
                                                                href="{{ url('edit-user/' . $user->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ url('change-permission/' . $user->id) }}">Change
                                                                Permissions</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ url('change-password/' . $user->id) }}">Change
                                                                Password</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- end row -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- ---------------------
                        end Zero Configuration
                    ---------------- -->
            </div>
        </div>
    </section>
    @section('pagescript')
        <script src="{{ asset('dist/libs/prismjs/prism.js') }}"></script>
        <script src="{{ asset('dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/datatable/datatable-basic.init.js') }}"></script>
    @endsection

    @section('pagecss')
        <link rel="stylesheet" href="{{ asset('dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    @endsection
</x-app-layout>
