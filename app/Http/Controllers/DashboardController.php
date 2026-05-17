<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\EmployeeProfile;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now()->toDateString();
        $period = now()->format('F Y');

        return view('dashboard', [
            'stats' => [
                'employees' => User::where('status', 'active')->count(),
                'departments' => Department::where('status', true)->count(),
                'presentToday' => AttendanceRecord::whereDate('work_date', $today)
                    ->whereNotNull('clock_in_at')
                    ->count(),
                'pendingLeaves' => LeaveRequest::where('status', 'pending')->count(),
                'payrollTotal' => Payroll::where('pay_period', $period)->sum('net_pay'),
            ],
            'recentEmployees' => User::with(['role', 'employeeProfile.department', 'employeeProfile.designation'])
                ->latest()
                ->limit(5)
                ->get(),
            'pendingLeaves' => LeaveRequest::with(['user', 'type'])
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get(),
            'announcements' => Announcement::with('publisher')
                ->where('status', 'published')
                ->latest('published_at')
                ->limit(4)
                ->get(),
        ]);
    }
}
