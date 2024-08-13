<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Edit User</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('users') }}">Users</a></li>
                            <li class="breadcrumb-item" aria-current="page">Edit User</li>
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
        <form action="{{ route('update.user', $user->id) }}" method="post">
            @csrf
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="inputcom" class="control-label col-form-label">Name</label>
                            <input name="username" class="form-control" id="inputcom" value="{{ $user->name }}" placeholder="Name" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Email</label>
                            <input name="email" class="form-control" id="inputcom" value="{{ $user->email }}" placeholder="Email" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label class="control-label col-form-label">Role</label>
                            <select class="form-select" name="role">
                                <option value="" disabled selected>Select Role</option>
                                @php
                                    $roles = DB::table('roles')->where('status', 1)->get();
                                @endphp

                                @foreach ($roles as $roles)
                                    <option value="{{ $roles->id }}" @if($roles->id == $user->role) selected @endif >{{ $roles->name }}</option>
                                @endforeach
                            </select>
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
</x-app-layout>
