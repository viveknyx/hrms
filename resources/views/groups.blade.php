<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Groups</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Groups</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div>
                            <a style="background: #5d87ff;" href="{{ url('add-new-group') }}"
                                class="justify-content-center btn mb-3 btn-primary align-items-center">
                                <i class="ti ti-plus fs-4 me-2"></i>
                                Add New Group
                            </a>
                            <a style="background: #fa896b;" href="{{ url('assign-project') }}"
                                class="justify-content-center btn mb-3 btn-danger align-items-center">
                                <i class="ti ti-file-code fs-4 me-2"></i>
                                Assign Project
                            </a>
                            <a style="background: #FFAE1F;" href="{{ url('assign-task') }}"
                                class="justify-content-center btn mb-3 btn-warning align-items-center">
                                <i class="ti ti-list-details fs-4 me-2"></i>
                                Assign Task
                            </a>
                            <a style="background: #13DEB9;" href="{{ url('schedule-session') }}"
                                class="justify-content-center btn mb-3 btn-success align-items-center">
                                <i class="ti ti-calendar-event fs-4 me-2"></i>
                                Schedule Session
                            </a>
                            <a style="background: #49BEFF;" href="{{ url('send-group-message') }}"
                                class="justify-content-center btn mb-3 btn-secondary align-items-center">
                                <i class="ti ti-send fs-4 me-2"></i>
                                Send Group Message
                            </a>
                        </div>
                        <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
                            <thead>
                                <!-- start row -->
                                <tr>
                                    <th>S.No</th>
                                    <th>Group Name</th>
                                    <th>Actions</th>
                                </tr>
                                <!-- end row -->
                            </thead>
                            <tbody>
                                @php
                                    $groups = DB::table('groups')->get();
                                    $count = 1;
                                @endphp

                                @foreach ($groups as $group)
                                    <!-- start row -->
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $group->name }}</td>
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
                                                            href="{{ url('edit-group/' . $group->id) }}">Edit</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ url('view-group/' . $group->id) }}">View</a></li>
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
