<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.login');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    Route::get('/employees', [EmployeeController::class, 'index'])
        ->middleware('permission:employees.view')
        ->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])
        ->middleware('permission:employees.manage')
        ->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])
        ->middleware('permission:employees.manage')
        ->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])
        ->middleware('permission:employees.manage')
        ->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])
        ->middleware('permission:employees.manage')
        ->name('employees.update');
    Route::patch('/employees/{employee}/status', [EmployeeController::class, 'toggleStatus'])
        ->middleware('permission:employees.manage')
        ->name('employees.status');

    Route::get('/departments', [DepartmentController::class, 'index'])
        ->middleware('permission:departments.manage')
        ->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'store'])
        ->middleware('permission:departments.manage')
        ->name('departments.store');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])
        ->middleware('permission:departments.manage')
        ->name('departments.update');
    Route::post('/designations', [DepartmentController::class, 'storeDesignation'])
        ->middleware('permission:departments.manage')
        ->name('designations.store');
    Route::put('/designations/{designation}', [DepartmentController::class, 'updateDesignation'])
        ->middleware('permission:departments.manage')
        ->name('designations.update');

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->middleware('permission:attendance.view')
        ->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])
        ->middleware('permission:attendance.view')
        ->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])
        ->middleware('permission:attendance.view')
        ->name('attendance.clock-out');
    Route::patch('/attendance/{attendance}', [AttendanceController::class, 'update'])
        ->middleware('permission:attendance.manage')
        ->name('attendance.update');
    Route::get('/mark-attendance/{value}', [AttendanceController::class, 'markAttendance']);

    Route::get('/leaves', [LeaveController::class, 'index'])
        ->middleware('permission:leave.view')
        ->name('leaves.index');
    Route::post('/leaves', [LeaveController::class, 'store'])
        ->middleware('permission:leave.view')
        ->name('leaves.store');
    Route::patch('/leaves/{leave}/approve', [LeaveController::class, 'approve'])
        ->middleware('permission:leave.manage')
        ->name('leaves.approve');
    Route::patch('/leaves/{leave}/reject', [LeaveController::class, 'reject'])
        ->middleware('permission:leave.manage')
        ->name('leaves.reject');

    Route::get('/payroll', [PayrollController::class, 'index'])
        ->middleware('permission:payroll.view')
        ->name('payroll.index');
    Route::post('/payroll', [PayrollController::class, 'store'])
        ->middleware('permission:payroll.manage')
        ->name('payroll.store');
    Route::patch('/payroll/{payroll}/paid', [PayrollController::class, 'markPaid'])
        ->middleware('permission:payroll.manage')
        ->name('payroll.paid');

    Route::get('/announcements', [AnnouncementController::class, 'index'])
        ->middleware('permission:announcements.view')
        ->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])
        ->middleware('permission:announcements.manage')
        ->name('announcements.store');

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles.manage')
        ->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:roles.manage')
        ->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:roles.manage')
        ->name('roles.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
