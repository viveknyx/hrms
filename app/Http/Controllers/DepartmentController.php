<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        return view('departments.index', [
            'departments' => Department::with('designations')->orderBy('name')->get(),
            'designations' => Designation::with('department')->orderBy('title')->get(),
            'managers' => User::where('status', 'active')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Department::create($this->validatedDepartment($request));

        return back()->with('success', 'Department created.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $department->update($this->validatedDepartment($request, $department));

        return back()->with('success', 'Department updated.');
    }

    public function storeDesignation(Request $request): RedirectResponse
    {
        Designation::create($request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['required', 'string', 'max:120'],
            'grade' => ['nullable', 'string', 'max:40'],
            'status' => ['required', 'boolean'],
        ]));

        return back()->with('success', 'Designation created.');
    }

    public function updateDesignation(Request $request, Designation $designation): RedirectResponse
    {
        $designation->update($request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['required', 'string', 'max:120'],
            'grade' => ['nullable', 'string', 'max:40'],
            'status' => ['required', 'boolean'],
        ]));

        return back()->with('success', 'Designation updated.');
    }

    private function validatedDepartment(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($department?->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'manager_user_id' => ['nullable', 'exists:users,id'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
