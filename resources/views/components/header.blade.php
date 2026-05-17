@php
    $user = auth()->user();
    $todayRecord = \App\Models\AttendanceRecord::where('user_id', $user->id)->whereDate('work_date', today())->first();
    $openAttendance = $todayRecord && $todayRecord->clock_in_at && ! $todayRecord->clock_out_at;
    $pendingLeaveCount = $user->hasPermission('leave.manage')
        ? \App\Models\LeaveRequest::where('status', 'pending')->count()
        : \App\Models\LeaveRequest::where('user_id', $user->id)->where('status', 'pending')->count();
@endphp

<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav quick-links d-none d-lg-flex align-items-center">
            <li class="nav-item me-3">
                <span class="badge bg-light-primary text-primary fw-semibold">
                    {{ now()->format('D, d M Y') }}
                </span>
            </li>
            <li class="nav-item">
                @if ($openAttendance)
                    <form action="{{ route('attendance.clock-out') }}" method="post">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">Clock Out</button>
                    </form>
                @else
                    <form action="{{ route('attendance.clock-in') }}" method="post">
                        @csrf
                        <button class="btn btn-sm btn-primary">Clock In</button>
                    </form>
                @endif
            </li>
        </ul>
        <div class="d-block d-lg-none fw-bolder text-primary">PeopleOps HRMS</div>
        <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="p-2"><i class="ti ti-dots fs-7"></i></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-bell-ringing"></i>
                            @if ($pendingLeaveCount > 0)
                                <div class="notification bg-primary rounded-circle"></div>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up p-3" aria-labelledby="drop2">
                            <h6 class="fw-semibold mb-1">Notifications</h6>
                            <p class="text-muted mb-0">
                                {{ $pendingLeaveCount }} pending leave {{ \Illuminate\Support\Str::plural('request', $pendingLeaveCount) }}.
                            </p>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                    style="width:35px;height:35px;background:{{ $user->avatar_color ?? '#2563eb' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                        style="width:64px;height:64px;background:{{ $user->avatar_color ?? '#2563eb' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-1 fs-3">{{ $user->name }}</h5>
                                        <span class="mb-1 d-block text-dark">{{ $user->role->name ?? 'Employee' }}</span>
                                        <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                            <i class="ti ti-mail fs-4"></i> {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="message-body">
                                    <a href="{{ route('profile.edit') }}" class="py-8 px-7 mt-8 d-flex align-items-center">
                                        <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                            <i class="ti ti-user fs-5 text-primary"></i>
                                        </span>
                                        <div class="w-75 d-inline-block v-middle ps-3">
                                            <h6 class="mb-1 bg-hover-primary fw-semibold">My Profile</h6>
                                            <span class="d-block text-dark">Account details</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="d-grid py-4 px-7 pt-8">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn btn-outline-primary w-100">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
