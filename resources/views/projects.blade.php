<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Projects</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Projects</li>
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
                                <a style="background: #5d87ff;" href="{{ url('add-new-project') }}"
                                    class="justify-content-center btn mb-3 btn-primary align-items-center">
                                    <i class="ti ti-plus fs-4 me-2"></i>
                                    Add New
                                </a>
                                <a style="background: #3dd9eb;" href="{{ url('categories') }}"
                                    class="justify-content-center btn mb-3 btn-success align-items-center">
                                    <i class="ti ti-category-2 fs-4 me-2"></i>
                                    Categories
                                </a>
                                <a style="background: #fa896b;" href="{{ url('project-types') }}"
                                    class="justify-content-center btn mb-3 btn-danger align-items-center">
                                    <i class="ti ti-binary-tree fs-4 me-2"></i>
                                    Types
                                </a>
                            </div>
                            <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
                                <thead>
                                    <!-- start row -->
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Types</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    <!-- end row -->
                                </thead>
                                <tbody>
                                    @php
                                        $projects = DB::table('projects')->get();
                                        $count = 1;
                                    @endphp

                                    @foreach ($projects as $project)
                                        <!-- start row -->
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $project->name }}</td>

                                            <td>{{ DB::table('project_categories')->where('id', $project->category)->pluck('name')->first() }}
                                            </td>

                                            <td>{{ DB::table('project_types')->where('id', $project->type)->pluck('name')->first() }}
                                            </td>

                                            @if ($project->status == 1)
                                                <td><span class="badge bg-light-success text-success fw-semibold fs-2 cursor-pointer"><a href="{{ route('change.status', ['type' => 1,'value' => 0, 'id' => $project->id]) }}" title="Click to Deactivate">Active</a></span>
                                                </td>
                                            @else
                                                <td><span class="badge bg-light-warning text-warning fw-semibold fs-2 cursor-pointer"><a href="{{ route('change.status', ['type' => 1,'value' => 1, 'id' => $project->id]) }}" title="Click to Activate">Inactive</a></span>
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
                                                                href="{{ url('view-project/'.$project->id) }}">View</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ url('edit-project/'.$project->id) }}">Edit</a></li>
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
        <!-- ---------------------------------------------- -->
        <!-- core files -->
        <!-- ---------------------------------------------- -->
        <script src="{{ asset('dist/libs/prismjs/prism.js') }}"></script>

        <!-- ---------------------------------------------- -->
        <!-- current page js files -->
        <!-- ---------------------------------------------- -->
        <script src="{{ asset('dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/js/datatable/datatable-basic.init.js') }}"></script>
    @endsection

    @section('pagecss')
        <link rel="shortcut icon" type="image/png"
            href="{{ asset('https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico') }}" />
        <link rel="stylesheet" href="{{ asset('dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
        <link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    @endsection
</x-app-layout>
