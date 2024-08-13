<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">{{ $project->name }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('projects') }}">Project</a></li>
                            <li class="breadcrumb-item" aria-current="page">View Project</li>
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
    <div class="card shadow border">
        <div class="card-body">
            <p class="pb-4">{{ DB::table('project_types')->where('id', $project->type)->pluck('name')->first() }}
                - {{ $project->project_number }}</p>
            <p style="font-size: 20px" class="pt-4 pb-4">Problem Statement :</p>
            <div>
                {!! $project->description !!}
            </div>

            <p style="font-size: 20px" class="pt-4 pb-4">Downlaod Files :</p>
            <div>
                <ul>
                    @foreach (explode('::', $project->files) as $file)
                        <li><a href="{{ $file }}" target="_blank">{{ $file }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-header bg-success">
                <h4 class="mb-0 text-white card-title text-center">Make Project Submission</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('upload.files', $project->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <label class="control-label col-form-label">Make Project Submission</label> --}}
                                <textarea name="solution" class="form-control" rows="3" placeholder="Enter your solution here..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-12">
                            {{-- <label class="control-label col-form-label">Select File</label> --}}
                            <input name="fileAttachments[]" class="form-control" type="file" id="formFile" multiple>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-12">
                            <div class="" id="linkdiv">
                                {{-- <label class="control-label col-form-label">Links</label> --}}
                                <div style="display: flex; justify-content: center; align-items: center" class="gap-2"
                                    id="linkdiv">
                                    <input name="projectLinks[]" class="form-control" id="input-primary"
                                        placeholder="Project Link Here" />
                                    <button type="button" class="btn btn-success btn-circle btn-xl me-1 mb-1" id="link-btn">
                                        <i class="ti ti-plus fs-3"></i>
                                    </button>
                                </div>
                                <div id="container-links"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="action-form text-center">
                            <button class="btn text-white px-4 waves-effect waves-light bg-success">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('pagescript')
        <script>

            var count = 1;

            document.getElementById("link-btn").onclick = function() {
                    $("#container-links").append("<div id='div" + count +
                    "' style='display: flex; justify-content: center; align-items: center' class='gap-2' '><input name='projectLinks[]' class='form-control' id='linkinput" +
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
</x-app-layout>
