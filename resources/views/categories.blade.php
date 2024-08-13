<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Categories</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted" href="{{ url('projects') }}">Project</a></li>
                            <li class="breadcrumb-item" aria-current="page">Categories</li>
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table border table-bordered text-nowrap">
                            <thead>
                                <!-- start row -->
                                <tr>
                                    <th>S.No</th>
                                    <th>Category Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                <!-- end row -->
                            </thead>
                            <tbody>
                                @php
                                    $projects = DB::table('project_categories')->get();
                                    $count = 1;
                                @endphp

                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $project->name }}</td>

                                        @if ($project->status == 1)
                                            <td><span
                                                    class="badge bg-light-success text-success fw-semibold fs-2 cursor-pointer"><a
                                                        href="{{ route('change.status', ['type' => 2, 'value' => 0, 'id' => $project->id]) }}"
                                                        title="Click to Deactivate">Active</a></span>
                                            </td>
                                        @else
                                            <td><span
                                                    class="badge bg-light-warning text-warning fw-semibold fs-2 cursor-pointer"><a
                                                        href="{{ route('change.status', ['type' => 2, 'value' => 1, 'id' => $project->id]) }}"
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
                                                    <li><span class="dropdown-item" onclick="onEditClick({{$project->id}}, '{{ $project->name }}')" style="cursor: pointer;" >Edit</span>
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table border table-bordered text-nowrap">
                            <thead>
                                <tr>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <form action="{{ route('add.project.category') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="inputcom" class="control-label col-form-label">Add New
                                                Category</label>

                                            <input class="form-control" name="addProjectCategoryField" id="inputcom"
                                                placeholder="Type Name" />
                                        </div>
                                        <button class="btn btn-info rounded-pill px-4 waves-effect waves-light"
                                            style="background: #539bff">Save</button>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card d-none" id="editCard">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zero_config" class="table border table-bordered text-nowrap">
                            <thead>
                                <tr>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <form action="{{ route('edit.project.category') }}" method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="namecom" class="control-label col-form-label">Edit Category</label>
                                            <input id="idcom" name="idcom" type="hidden">
                                            <input class="form-control" id="namecom" name="namecom" placeholder="Edit Category Name" />
                                        </div>
                                        <button class="btn btn-info rounded-pill px-4 waves-effect waves-light"
                                            style="background: #539bff">
                                            Save
                                        </button>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('pagescript')
        <script>
            const editCard = document.getElementById("editCard");
            function onEditClick(id, name) {
                editCard.classList.remove("d-none");
                const categoryId = $("#idcom").val(id)
                const categoryName = $("#namecom").val(name)
                $("#idcom").val(id)
                $("#namecom").val(name)
            }
        </script>
    @endsection
</x-app-layout>

