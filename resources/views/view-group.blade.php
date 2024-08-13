<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">View Group</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('groups') }}">Groups</a></li>
                            <li class="breadcrumb-item" aria-current="page">View Group</li>
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

    <div>
        <a style="background: #5d87ff;" href="{{ url('add-new-member/' . $group->id) }}"
            class="justify-content-center btn mb-3 btn-primary align-items-center">
            <i class="ti ti-plus fs-4 me-2"></i>
            Add New Members
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
                    <thead>
                        <!-- start row -->
                        <tr>
                            <th>S.no</th>
                            <th>Student Name</th>
                            <th>Projects Submitted</th>
                            <th>Projects Evaluated</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                    </thead>
                    <tbody>
                        @php
                            $groupId = $group->id;
                            $allMembers = DB::table('users')
                                ->where('users.group_id', 'like', "%$groupId%")
                                ->get();
                            $count = 1;
                        @endphp

                        @foreach ($allMembers as $member)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $member->name }}</td>
                                <td>3</td>
                                <td>0</td>
                                <td>
                                    <div class="btn-group mb-2">
                                        <button type="button" style="background: #2a3547;"
                                            class="btn btn-dark dropdown-toggle text-white" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="ti ti-settings fs-4"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('remove.member', ['groupId' => $groupId, 'id' => $member->id]) }}"
                                                    style="cursor: pointer;">
                                                    @csrf
                                                    Remove
                                                </a>
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
