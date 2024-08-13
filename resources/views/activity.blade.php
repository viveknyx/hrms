<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Activity</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Activity</li>
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
                            <a style="background: #fa896b;" href="{{ url('add-new-activity') }}"
                                class="justify-content-center btn mb-3 btn-danger align-items-center">
                                <i class="ti ti-file-code fs-4 me-2"></i>
                                Add New Activity
                            </a>
                        </div>
                        <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
                            <thead>
                                <!-- start row -->
                                <tr>
                                    <th>S.No</th>
                                    <th>Title</th>
                                    {{-- <th>Created by</th> --}}
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                                <!-- end row -->
                            </thead>
                            <tbody>
                                @php
                                    $activities = DB::table('activity')->get();
                                    $count = 1;
                                @endphp

                                @foreach ($activities as $activity)
                                    <!-- start row -->
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $activity->title }}</td>
                                        {{-- <td>
                                            @php
                                                $createdByName = DB::table('users')
                                                    ->where('id', $activity->created_by_user_id)
                                                    ->first();
                                                $name = $createdByName ? $createdByName->name : 'N/A';
                                                echo $name;
                                            @endphp
                                        </td> --}}
                                        <td>{{ $activity->start_date }}</td>
                                        <td>{{ $activity->end_date }}</td>

                                        <td>
                                            <div class="btn-group mb-2">
                                                <button type="button" style="background: #2a3547;"
                                                    class="btn btn-dark dropdown-toggle text-white"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="ti ti-settings fs-4"></i>
                                                </button>
                                                <ul class="dropdown-menu cursor-pointer">
                                                    <li><a class="dropdown-item" href="{{ url('edit-activity/' . $activity->id) }}">Edit</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('remove.activity', ['id' => $activity->id]) }}">Remove</a></li>

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
