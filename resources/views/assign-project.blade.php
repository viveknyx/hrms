<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Assign Project</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('groups') }}">Groups</a></li>
                            <li class="breadcrumb-item" aria-current="page">Assign Project</li>
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
    <div class="card w-100">
        <form action="{{ route('assign.project.db') }}" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Project Type</label>
                            <select class="form-select" name="projectType">
                                <option value="" disabled selected>Select Project Type</option>
                                @php
                                    $types = DB::table('project_types')
                                        ->where('status', 1)
                                        ->get();
                                @endphp

                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Project Name</label>
                            <select class="form-select" name="projectname">
                                <option value="" disabled selected>Select Project Name</option>
                                @php
                                    $projects = DB::table('projects')
                                        ->where('status', 1)
                                        ->get();
                                @endphp

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label class="control-label col-form-label">Groups</label>
                        <div class="gap-2" style="display: flex; justify-content: center; align-items: center">
                            <select class="select2 form-control" name="groups[]" id="select2-language" multiple="multiple">
                                {{-- <option value="" disabled selected>Select User</option> --}}
                                @php
                                    $groups = DB::table('groups')->get();
                                @endphp
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Start Date</label>
                            <div class="form-group">
                                <input name="startdate" value="" type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="control-label col-form-label">End Date</label>
                            <div class="form-group">
                                <input name="enddate" value="" type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="action-form">
                    <div class="mb-3 text-start">
                        <button class="btn btn-info rounded-pill px-4 waves-effect waves-light"
                            style="background: #539bff">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @section('pagescript')
        <script src="{{ asset('dist/libs/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('dist/libs/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('dist/js/forms/select2.init.js') }}"></script>
    @endsection

    @section('pagecss')
        <link rel="stylesheet" href="{{ asset('dist/libs/select2/dist/css/select2.min.css') }}">
        <style>
            .select2-container--classic .select2-selection--multiple .select2-selection__choice,
            .select2-container--default .select2-selection--multiple .select2-selection__choice,
            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                background-color: #539bff;
                border-color: #539bff;
                color: #fff;
            }
        </style>
    @endsection
</x-app-layout>
