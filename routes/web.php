<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//clock in/out
Route::get('mark-attendance/{value}', [AttendanceController::class, 'markAttendance']);

//projects
Route::get('projects', [ProjectController::class, 'projects']);
Route::get('add-new-project', [ProjectController::class, 'addNewProject']);
Route::get('edit-project/{id}', [ProjectController::class, 'editProject']);
Route::get('view-project/{id}', [ProjectController::class, 'viewProject']);
Route::get('categories', [ProjectController::class, 'categories']);
Route::get('project-types', [ProjectController::class, 'projectTypes']);
Route::post('upload-files/{id}', [ProjectController::class, 'uploadFiles'])->name('upload.files');

//users
Route::get('users', [UserController::class, 'users']);
Route::get('roles', [UserController::class, 'roles']);
Route::get('add-new-role', [UserController::class, 'addNewRole']);
Route::get('edit-role/{id}', [UserController::class, 'editRole']);
Route::get('add-new-user', [UserController::class, 'addNewUser']);
Route::get('edit-user/{id}', [UserController::class, 'editUser']);
Route::get('change-password/{id}', [UserController::class, 'changePassword']);
Route::get('change-permission/{id}', [UserController::class, 'changePermission']);

//groups
Route::get('groups', [GroupController::class, 'groups']);
Route::get('view-group/{id}', [GroupController::class, 'viewGroup']);
Route::get('add-new-group', [GroupController::class, 'addNewGroup']);
Route::get('add-new-member/{id}', [GroupController::class, 'addNewMember']);
Route::get('edit-group/{id}', [GroupController::class, 'editGroup']);
Route::get('assign-project', [GroupController::class, 'assignProject']);
Route::get('assign-task', [GroupController::class, 'assignTask']);
Route::get('schedule-session', [GroupController::class, 'scheduleSession']);
Route::get('send-group-message', [GroupController::class, 'sendGroupMessage']);

//activity
Route::get('activity', [ActivityController::class, 'activity']);
Route::get('add-new-activity', [ActivityController::class, 'addNewActivity']);
Route::get('edit-activity/{id}', [ActivityController::class, 'editActivity']);
