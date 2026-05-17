@php
    $user = auth()->user();
    $links = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'ti ti-layout-dashboard', 'permission' => 'dashboard.view'],
        ['label' => 'Employees', 'route' => 'employees.index', 'icon' => 'ti ti-users', 'permission' => 'employees.view'],
        ['label' => 'Departments', 'route' => 'departments.index', 'icon' => 'ti ti-building', 'permission' => 'departments.manage'],
        ['label' => 'Attendance', 'route' => 'attendance.index', 'icon' => 'ti ti-clock-hour-4', 'permission' => 'attendance.view'],
        ['label' => 'Leave', 'route' => 'leaves.index', 'icon' => 'ti ti-calendar-time', 'permission' => 'leave.view'],
        ['label' => 'Payroll', 'route' => 'payroll.index', 'icon' => 'ti ti-cash', 'permission' => 'payroll.view'],
        ['label' => 'Announcements', 'route' => 'announcements.index', 'icon' => 'ti ti-speakerphone', 'permission' => 'announcements.view'],
        ['label' => 'Roles', 'route' => 'roles.index', 'icon' => 'ti ti-shield-lock', 'permission' => 'roles.manage'],
    ];
@endphp

<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between px-4 py-3">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img text-decoration-none">
                <span class="fw-bolder fs-6 text-primary">PeopleOps</span>
                <span class="fw-semibold text-dark ms-1">HRMS</span>
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Workspace</span>
                </li>
                @foreach ($links as $link)
                    @if ($user?->hasPermission($link['permission']))
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeIs($link['route']) ? 'active' : '' }}"
                                href="{{ route($link['route']) }}" aria-expanded="false">
                                <span><i class="{{ $link['icon'] }}"></i></span>
                                <span class="hide-menu">{{ $link['label'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3 mx-3">
            <div class="hstack gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                    style="width:40px;height:40px;background:{{ $user->avatar_color ?? '#2563eb' }}">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="john-title overflow-hidden">
                    <h6 class="mb-0 fs-3 fw-semibold text-truncate">{{ $user->name }}</h6>
                    <span class="fs-2 text-dark text-truncate d-block">{{ $user->role->name ?? 'Employee' }}</span>
                </div>
            </div>
        </div>
    </div>
</aside>
