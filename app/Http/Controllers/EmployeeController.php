<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = User::with(['role', 'employeeProfile.department', 'employeeProfile.designation'])
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->whereHas('employeeProfile', fn ($profile) => $profile->where('department_id', $request->department));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('employees.index', [
            'employees' => $employees,
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('employees.form', $this->formData(new User(), new EmployeeProfile()));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?: 'password'),
                'role_id' => $data['role_id'],
                'employee_code' => $data['employee_code'],
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'],
                'avatar_color' => $data['avatar_color'] ?? '#2563eb',
            ]);

            $user->employeeProfile()->create($this->profilePayload($data));
        });

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(User $employee): View
    {
        $employee->load('employeeProfile');

        return view('employees.form', $this->formData($employee, $employee->employeeProfile ?? new EmployeeProfile()));
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        $data = $this->validated($request, $employee);

        DB::transaction(function () use ($data, $employee) {
            $employee->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'employee_code' => $data['employee_code'],
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'],
                'avatar_color' => $data['avatar_color'] ?? $employee->avatar_color,
            ]);

            if (! empty($data['password'])) {
                $employee->update(['password' => Hash::make($data['password'])]);
            }

            $employee->employeeProfile()->updateOrCreate(
                ['user_id' => $employee->id],
                $this->profilePayload($data)
            );
        });

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function toggleStatus(User $employee): RedirectResponse
    {
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $employee->update([
            'status' => $employee->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Employee status updated.');
    }

    private function formData(User $employee, EmployeeProfile $profile): array
    {
        return [
            'employee' => $employee,
            'profile' => $profile,
            'roles' => Role::where('status', true)->orderBy('name')->get(),
            'departments' => Department::where('status', true)->orderBy('name')->get(),
            'designations' => Designation::where('status', true)->orderBy('title')->get(),
            'managers' => User::where('status', 'active')->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?User $employee = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee?->id)],
            'password' => [$employee ? 'nullable' : 'required', 'string', 'min:8'],
            'role_id' => ['required', 'exists:roles,id'],
            'employee_code' => ['required', 'string', 'max:40', Rule::unique('users', 'employee_code')->ignore($employee?->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave', 'probation'])],
            'avatar_color' => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'manager_user_id' => ['nullable', 'exists:users,id'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:40'],
            'joining_date' => ['nullable', 'date'],
            'employment_type' => ['required', 'string', 'max:80'],
            'work_location' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:40'],
            'bank_name' => ['nullable', 'string', 'max:120'],
            'bank_account_last4' => ['nullable', 'string', 'size:4'],
            'salary' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function profilePayload(array $data): array
    {
        return collect($data)->only([
            'department_id',
            'designation_id',
            'manager_user_id',
            'date_of_birth',
            'gender',
            'joining_date',
            'employment_type',
            'work_location',
            'address',
            'emergency_contact_name',
            'emergency_contact_phone',
            'bank_name',
            'bank_account_last4',
            'salary',
        ])->all();
    }
}
