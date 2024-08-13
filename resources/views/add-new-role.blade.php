<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Add New Role</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('users') }}">Users</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a
                                    class="text-muted"href="{{ url('roles') }}">Roles</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add New Role</li>
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
        <form action="{{ route('add.role') }}" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="row">
                    @php
                        // $roles = DB::table('roles');
                        // $roleArray = explode('::');
                    @endphp
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="inputcom" class="control-label col-form-label">Role Name</label>
                            <input name="roleName" class="form-control" id="inputcom"
                                placeholder="Role Name Here" />
                        </div>
                        <div class="card w-100">
                            <div class="card-body">
                                <div class="mb-4">
                                    <h5 class="mb-0">Permissions</h5>
                                </div>
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed gap-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                                aria-expanded="false" aria-controls="flush-collapseOne">
                                                {{-- <input class="form-check-input" type="checkbox" value=""
                                                    id="selectAllList1" onclick="toggleProjects(this)" /> --}}
                                                Projects
                                            </button>
                                        </h2>
                                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="aa"
                                                            id="list1" name="projects[]" />
                                                        <label class="form-check-label" for="list1">
                                                            Add New Project
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="ab"
                                                            id="list2" name="projects[]" />
                                                        <label class="form-check-label" for="list2">
                                                            View Project
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="ac"
                                                            id="list3" name="projects[]" />
                                                        <label class="form-check-label" for="list3">
                                                            Edit Project
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingTwo">
                                            <button class="accordion-button collapsed gap-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                                {{-- <input class="form-check-input" type="checkbox" value=""
                                                    id="selectAllList2" onclick="toggleProjectCategory(this)" /> --}}
                                                Project Category
                                            </button>
                                        </h2>
                                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="ba" id="list4" name="projectCategory[]" />
                                                        <label class="form-check-label" for="list4">
                                                            Add New Project Category
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="bb" id="list5" name="projectCategory[]" />
                                                        <label class="form-check-label" for="list5">
                                                            View Project Category
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="bc" id="list6" name="projectCategory[]" />
                                                        <label class="form-check-label" for="list6">
                                                            Edit Project Category
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingThree">
                                            <button class="accordion-button collapsed gap-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                                aria-expanded="false" aria-controls="flush-collapseThree">
                                                {{-- <input class="form-check-input" type="checkbox" value=""
                                                    id="selectAllList3" onclick="toggleProjectType(this)" /> --}}
                                                Project Type
                                            </button>
                                        </h2>
                                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingThree"
                                            data-bs-parent="#accordionFlushExample">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="ca" id="list7" name="projectType[]" />
                                                        <label class="form-check-label" for="list7">
                                                            Add New Project Type
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="cb" id="list8" name="projectType[]" />
                                                        <label class="form-check-label" for="list8">
                                                            View Project Type
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="cc" id="list9" name="projectType[]" />
                                                        <label class="form-check-label" for="list9">
                                                            Edit Project Type
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
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
        <script>
            /*function test() {
                        $('#selectAllList3').indeterminate = true;
                    }

                    function toggleProjects(source) {
                        var checkboxes = document.getElementsByName('projects');
                        var addNewProject = document.getElementById('list1');
                        // for (var i = 0; i < checkboxes.length; i++) {
                        //     checkboxes[i].checked = source.checked;
                        // }
                    }

                    function toggleProjectCategory(source) {
                        var checkboxes = document.getElementsByName('projectCategory');
                        for (var i = 0; i < checkboxes.length; i++) {
                            checkboxes[i].checked = source.checked;
                        }
                    }

                    function toggleProjectType(source) {
                        var checkboxes = document.getElementsByName('projectType');
                        for (var i = 0; i < checkboxes.length; i++) {
                            checkboxes[i].checked = source.checked;
                        }
                    }*/
        </script>
    @endsection
</x-app-layout>
