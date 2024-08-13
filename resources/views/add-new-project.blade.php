<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Add New Project</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted" href="{{ url('projects') }}">Project</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Project</li>
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
        <form action="{{ route('add.project') }}" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputcom" class="control-label col-form-label">Project Name</label>
                            <input name="projectName" class="form-control" id="inputcom" placeholder="Project Name Here" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Project Type</label>
                            <select class="form-select" name="projectType">
                                <option value="" disabled selected>Select Project Type</option>
                                @php
                                    $types = DB::table('project_types')->where('status', 1)->get();
                                @endphp

                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Project Number</label>
                            <input name="projectNumber" class="form-control" id="inputcom" placeholder="Project Number Here" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Project Category</label>
                            <select class="form-select" name="projectCategory">
                                <option value="" disabled selected>Select Project Category</option>
                                @php
                                    $categories = DB::table('project_categories')->where('status', 1)->get();
                                @endphp

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="editor1"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3" id="linkdiv">
                            <label class="control-label col-form-label">Links</label>
                            <div style="display: flex; justify-content: center; align-items: center" class="gap-2"
                                id="linkdiv">
                                <input name="projectLinks[]" class="form-control m-2" id="input-primary" placeholder="Project Link Here" />
                                <button type="button" class="btn btn-secondary btn-circle btn-xl me-1 mb-1"
                                    style="background: #539bff" id="link-btn">
                                    <i class="ti ti-plus fs-3"></i>
                                </button>
                            </div>
                            <div id="container-links"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="action-form">
                    <div class="mb-3 text-start">
                        <button class="btn btn-info rounded-pill px-4 waves-effect waves-light" style="background: #539bff">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @section('pagescript')
        <script src="{{ asset('dist/libs/prismjs/prism.js') }}"></script>
        <script src="{{ asset('dist/libs/quill/dist/quill.min.js') }}"></script>
        <script src="{{ asset('https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace( 'editor1' );

            var count = 1;

            document.getElementById("link-btn").onclick = function() {
                    $("#container-links").append("<div id='div" + count +
                    "' style='display: flex; justify-content: center; align-items: center' class='gap-2' '><input name='projectLinks[]' class='form-control m-2' id='linkinput" +
                    count +
                    "' placeholder='Project Link Here' /><button style='background: #fa896b;' onClick='deleteLink(" +
                    count++ +
                    ")' type='button' class='btn btn-danger btn-circle btn-xl me-1 mb-1'><i class='ti ti-trash fs-5'></i></button></div>");
            }

            function deleteLink(x) {
                var container = document.getElementById("div" + x);
                container.innerHTML = "";
            }
        </script>
    @endsection

    @section('pagecss')
        <link rel="stylesheet" href="{{ asset('dist/libs/quill/dist/quill.snow.css') }}">
    @endsection
</x-app-layout>
