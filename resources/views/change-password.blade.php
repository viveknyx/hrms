<x-app-layout>
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8" style="font-size: 30px">Change Password</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{ url('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted"
                                    href="{{ url('users') }}">Users</a></li>
                            <li class="breadcrumb-item" aria-current="page">Change Password</li>
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
    <div class="card">
        <div class="card-body">
            <form action="{{ route('update.password', $user->id) }}" method="post">
                @csrf
                <div class="form-floating mb-3">
                    <input type="password" name="newpassword" class="form-control border border-warning" style="border-radius: 10px" placeholder="New Password" />
                    <label><i class="ti ti-lock me-2 fs-4 text-warning"></i><span
                            class="border-start border-warning ps-3">New Password</span></label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="confirmpassword" class="form-control border border-warning" style="border-radius: 10px" placeholder="CPassword" />
                    <label><i class="ti ti-lock me-2 fs-4 text-warning"></i><span
                            class="border-start border-warning ps-3">Confirm Password</span></label>
                </div>

                <div class="d-md-flex align-items-center">
                    <div class="mt-3 mt-md-0 ms-auto">
                        <button type="submit" class="btn btn-warning font-medium rounded-pill px-4"
                            style="background-color: #FFAE1F">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-send me-2 fs-4"></i>
                                Submit
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
