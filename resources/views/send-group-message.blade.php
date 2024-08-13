<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Send Group Message</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('groups') }}">Groups</a></li>
                            <li class="breadcrumb-item" aria-current="page">Send Group Message</li>
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
        <form action="{{ route('send.group.message.db') }}" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="inputcom" class="control-label col-form-label">Message Title</label>
                            <input name="messageTitle" class="form-control" id="inputcom" placeholder="Message Title" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label class="control-label col-form-label">Groups</label>
                        <div class="gap-2" style="display: flex; justify-content: center; align-items: center">
                            <select class="select2 form-control" name="groups[]" id="select2-language" multiple="multiple">
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
                    <div class="col-md-12">
                        <label for="desc" class="control-label col-form-label">Description</label>
                        <textarea id="desc" name="editor1"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mt-3">
                            <input name="fileAttachments[]" class="form-control-file" type="file" id="formFile"
                                multiple>
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
        <script src="{{ asset('https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('editor1');
        </script>
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
