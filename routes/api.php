<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//projects
Route::post('add-project-type', [ProjectController::class, 'addProjectType'])->name('add.project.type');
Route::post('add-project-category', [ProjectController::class, 'addProjectCategory'])->name('add.project.category');
Route::post('edit-project-category', [ProjectController::class, 'editProjectCategory'])->name('edit.project.category');
Route::post('edit-project-type', [ProjectController::class, 'editProjecttype'])->name('edit.project.type');
Route::post('add-project', [ProjectController::class, 'addProject'])->name('add.project');
Route::post('update-project/{id}', [ProjectController::class, 'updateProject'])->name('update.project');
Route::get('change-status/{type}/{value}/{id}', [ProjectController::class, 'changeStatus'])->name('change.status');

//users
Route::post('add-role', [UserController::class, 'addRole'])->name('add.role');
Route::post('update-role/{id}', [UserController::class, 'updateRole'])->name('update.role');
Route::post('add-user', [UserController::class, 'addUser'])->name('add.user');
Route::post('update-password/{id}', [UserController::class, 'updatePassword'])->name('update.password');
Route::post('update-permission/{id}', [UserController::class, 'updatePermission'])->name('update.permission');
Route::post('update-user/{id}', [UserController::class, 'updateUser'])->name('update.user');

//groups
Route::post('add-group', [GroupController::class, 'addGroup'])->name('add.group');
Route::post('add-member/{id}', [GroupController::class, 'addMember'])->name('add.member');
Route::post('update-group/{id}', [GroupController::class, 'updateGroup'])->name('update.group');
Route::get('remove-member/{groupId}/{id}', [GroupController::class, 'removeMember'])->name('remove.member');
Route::post('assign-project-db', [GroupController::class, 'assignProjectDB'])->name('assign.project.db');
Route::post('assign-task-db', [GroupController::class, 'assignTaskDB'])->name('assign.task.db');
Route::post('schedule-session-db', [GroupController::class, 'scheduleSessionDB'])->name('schedule.session.db');
Route::post('send-group-message-db', [GroupController::class, 'sendGroupMessageDB'])->name('send.group.message.db');

//activity
Route::get('add-new-activity', [ActivityController::class, 'addNewActivity']);
Route::post('add-activity', [ActivityController::class, 'addActivity'])->name('add.activity');
Route::get('remove-activity/{id}', [ActivityController::class, 'removeActivity'])->name('remove.activity');
Route::post('update-activity/{id}', [ActivityController::class, 'updateActivity'])->name('update.activity');