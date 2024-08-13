<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">{{ $project->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted" href="{{ url('projects') }}">Project</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit Project</li>
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
        <form action="{{ route('update.project', $project->id) }}" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputcom" class="control-label col-form-label">Project Name</label>
                            <input name="projectName" class="form-control" id="inputcom" value="{{ $project->name }}" placeholder="Project Name Here" />
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
                                    <option value="{{ $type->id }}" @if($type->id == $project->type) selected @endif>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Project Number</label>
                            <input name="projectNumber" class="form-control" id="inputcom" value="{{ $project->project_number }}" placeholder="Project Number Here" />
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
                                    <option value="{{ $category->id }}" @if($category->id == $project->category) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <textarea name="editor1">{{ $project->description }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3" id="linkdiv">
                            <label class="control-label col-form-label">Links</label>
                            <div id="linkdiv">
                                @php
                                    $count = 1;
                                    $firstElement = null;
                                    $fileArray = explode('::', $project->files);
                                @endphp

                                @if(count($fileArray) > 0)
                                    <div style="display: flex; justify-content: center; align-items: center">
                                        <input name="projectLinks[]" value="{{ $fileArray[0] }}" class="form-control m-2" id="input-primary" placeholder="Project Link Here" />
                                        <button type="button" class="btn btn-secondary btn-circle btn-xl me-1 mb-1"
                                            style="background: #539bff" id="link-btn">
                                            <i class="ti ti-plus fs-3"></i>
                                        </button>
                                    </div>

                                    @foreach ($fileArray as $index => $file)
                                        @if($index > 0)
                                            <div id="{{ "newDiv".$count }}" style="display: flex; justify-content: center; align-items: center" class="gap-2">
                                                <input name="projectLinks[]" value="{{ $file }}" class="form-control m-2" id="linkinput" placeholder="Project Link Here" />
                                                <button style="background: #fa896b;" onClick="newDeleteLink({{ $count }})" type="button" class="btn btn-danger btn-circle btn-xl me-1 mb-1">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </div>
                                            @php $count = $count + 1 @endphp
                                        @endif
                                    @endforeach
                                @else
                                    <h3>No Links Found</h3>
                                @endif

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
            // var quill = new Quill("#editor", {
            //     theme: "snow",
            // });

            CKEDITOR.replace( 'editor1' );

            var count = 1;

            document.getElementById("link-btn").onclick = function() {
                // var container = document.getElementById("container-links");

                // container.innerHTML += "<div id='div" + count +
                //     "' style='display: flex; justify-content: center; align-items: center' class='gap-2' '><input name='projectLinks[]' class='form-control m-2' id='linkinput" +
                //     count +
                //     "' placeholder='Project Link Here' /><button style='background: #fa896b;' onClick='deleteLink(" +
                //     count++ +
                //     ")' type='button' class='btn btn-danger btn-circle btn-xl me-1 mb-1'><i class='ti ti-trash fs-5'></i></button></div>";

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

            function newDeleteLink(x) {
                var container = document.getElementById("newDiv" + x);
                container.innerHTML = "";
            }
        </script>
    @endsection

    @section('pagecss')
        <link rel="stylesheet" href="{{ asset('dist/libs/quill/dist/quill.snow.css') }}">
    @endsection
</x-app-layout>
