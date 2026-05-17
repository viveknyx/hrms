<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeProfile;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedRoles();
            $this->seedDepartments();
            $this->seedEmployees();
            $this->seedLeaves();
            $this->seedAttendance();
            $this->seedPayroll();
            $this->seedAnnouncements();
            $this->seedHolidaysAndAssets();
        });
    }

    private function seedRoles(): void
    {
        $all = Role::allPermissionKeys();

        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full platform access for HRMS owners.', 'permissions' => $all],
            ['name' => 'HR Manager', 'slug' => 'hr-manager', 'description' => 'Runs people operations, attendance, leave, payroll, and announcements.', 'permissions' => $all],
            ['name' => 'People Ops Executive', 'slug' => 'people-ops-executive', 'description' => 'Manages employee records, departments, leave, attendance, and announcements.', 'permissions' => ['dashboard.view', 'announcements.view', 'employees.view', 'employees.manage', 'departments.manage', 'attendance.view', 'attendance.manage', 'leave.view', 'leave.manage', 'announcements.manage']],
            ['name' => 'Payroll Specialist', 'slug' => 'payroll-specialist', 'description' => 'Handles payroll while viewing employee and attendance context.', 'permissions' => ['dashboard.view', 'announcements.view', 'employees.view', 'attendance.view', 'leave.view', 'payroll.view', 'payroll.manage']],
            ['name' => 'Team Lead', 'slug' => 'team-lead', 'description' => 'Reviews team records, attendance, and leave requests.', 'permissions' => ['dashboard.view', 'announcements.view', 'employees.view', 'attendance.view', 'leave.view', 'leave.manage', 'payroll.view']],
            ['name' => 'Employee', 'slug' => 'employee', 'description' => 'Self-service access for attendance, leave, announcements, and payslips.', 'permissions' => ['dashboard.view', 'announcements.view', 'attendance.view', 'leave.view', 'payroll.view']],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role + ['is_system' => true, 'status' => true]);
        }
    }

    private function seedDepartments(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'People operations, policy, engagement, and compliance.'],
            ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Product engineering, QA, DevOps, and internal tooling.'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Payroll, accounting, procurement, and reporting.'],
            ['name' => 'Sales', 'code' => 'SAL', 'description' => 'Revenue operations, account management, and customer acquisition.'],
            ['name' => 'Customer Success', 'code' => 'CS', 'description' => 'Implementation, training, renewals, and support.'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(['code' => $department['code']], $department + ['status' => true]);
        }

        $designations = [
            'HR' => [['HR Manager', 'M3'], ['People Ops Executive', 'L2'], ['Talent Acquisition Specialist', 'L2']],
            'ENG' => [['Engineering Manager', 'M3'], ['Senior Software Engineer', 'L4'], ['QA Analyst', 'L2'], ['DevOps Engineer', 'L3']],
            'FIN' => [['Finance Manager', 'M3'], ['Payroll Specialist', 'L3'], ['Accounts Executive', 'L2']],
            'SAL' => [['Sales Manager', 'M3'], ['Account Executive', 'L2'], ['Sales Development Representative', 'L1']],
            'CS' => [['Customer Success Manager', 'M3'], ['Implementation Specialist', 'L2'], ['Support Engineer', 'L2']],
        ];

        foreach ($designations as $departmentCode => $items) {
            $department = Department::where('code', $departmentCode)->first();

            foreach ($items as [$title, $grade]) {
                Designation::updateOrCreate(
                    ['department_id' => $department->id, 'title' => $title],
                    ['grade' => $grade, 'status' => true]
                );
            }
        }
    }

    private function seedEmployees(): void
    {
        $roleIds = Role::pluck('id', 'slug');
        $departmentIds = Department::pluck('id', 'code');
        $designationIds = Designation::pluck('id', 'title');

        $employees = [
            ['Eren Sharma', 'eren@gmail.com', 'EMP-1001', 'super-admin', 'HR', 'HR Manager', null, 'active', '#2563eb', '9876543210', '2021-04-12', 185000],
            ['Priya Nair', 'priya.nair@peopleops.test', 'EMP-1002', 'hr-manager', 'HR', 'HR Manager', 'eren@gmail.com', 'active', '#0f766e', '9876501122', '2022-01-10', 165000],
            ['Ananya Rao', 'ananya.rao@peopleops.test', 'EMP-1003', 'people-ops-executive', 'HR', 'People Ops Executive', 'priya.nair@peopleops.test', 'active', '#7c3aed', '9876501133', '2023-02-06', 82000],
            ['Vikram Singh', 'vikram.singh@peopleops.test', 'EMP-1004', 'team-lead', 'ENG', 'Engineering Manager', 'eren@gmail.com', 'active', '#dc2626', '9876501144', '2020-09-18', 210000],
            ['Rohan Mehta', 'rohan.mehta@peopleops.test', 'EMP-1005', 'employee', 'ENG', 'Senior Software Engineer', 'vikram.singh@peopleops.test', 'active', '#ea580c', '9876501155', '2022-07-21', 145000],
            ['Aisha Khan', 'aisha.khan@peopleops.test', 'EMP-1006', 'employee', 'ENG', 'QA Analyst', 'vikram.singh@peopleops.test', 'active', '#0891b2', '9876501166', '2023-08-14', 76000],
            ['Neha Iyer', 'neha.iyer@peopleops.test', 'EMP-1007', 'payroll-specialist', 'FIN', 'Payroll Specialist', 'priya.nair@peopleops.test', 'active', '#4f46e5', '9876501177', '2022-11-01', 92000],
            ['Kabir Shah', 'kabir.shah@peopleops.test', 'EMP-1008', 'employee', 'FIN', 'Accounts Executive', 'neha.iyer@peopleops.test', 'probation', '#16a34a', '9876501188', '2026-02-03', 58000],
            ['Sneha Kapoor', 'sneha.kapoor@peopleops.test', 'EMP-1009', 'team-lead', 'SAL', 'Sales Manager', 'priya.nair@peopleops.test', 'active', '#be123c', '9876501199', '2021-12-07', 150000],
            ['Arjun Menon', 'arjun.menon@peopleops.test', 'EMP-1010', 'employee', 'SAL', 'Account Executive', 'sneha.kapoor@peopleops.test', 'active', '#9333ea', '9876501200', '2024-04-15', 72000],
            ['Meera Joshi', 'meera.joshi@peopleops.test', 'EMP-1011', 'team-lead', 'CS', 'Customer Success Manager', 'priya.nair@peopleops.test', 'active', '#047857', '9876501211', '2021-06-28', 132000],
            ['Dev Patel', 'dev.patel@peopleops.test', 'EMP-1012', 'employee', 'CS', 'Support Engineer', 'meera.joshi@peopleops.test', 'on_leave', '#0369a1', '9876501222', '2023-10-09', 68000],
        ];

        $usersByEmail = [];

        foreach ($employees as [$name, $email, $code, $role, $department, $designation, $managerEmail, $status, $color, $phone, $joiningDate, $salary]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role_id' => $roleIds[$role],
                    'employee_code' => $code,
                    'status' => $status,
                    'phone' => $phone,
                    'avatar_color' => $color,
                ]
            );

            $usersByEmail[$email] = $user;
        }

        foreach ($employees as [$name, $email, $code, $role, $department, $designation, $managerEmail, $status, $color, $phone, $joiningDate, $salary]) {
            EmployeeProfile::updateOrCreate(
                ['user_id' => $usersByEmail[$email]->id],
                [
                    'department_id' => $departmentIds[$department],
                    'designation_id' => $designationIds[$designation],
                    'manager_user_id' => $managerEmail ? $usersByEmail[$managerEmail]->id : null,
                    'date_of_birth' => now()->subYears(rand(25, 42))->subDays(rand(1, 300))->toDateString(),
                    'gender' => in_array($name, ['Priya Nair', 'Ananya Rao', 'Aisha Khan', 'Neha Iyer', 'Sneha Kapoor', 'Meera Joshi'], true) ? 'Female' : 'Male',
                    'joining_date' => $joiningDate,
                    'employment_type' => 'Full-time',
                    'work_location' => in_array($department, ['ENG', 'CS'], true) ? 'Hybrid - Bengaluru' : 'Office - Mumbai',
                    'address' => 'Flat '.rand(10, 904).', Sector '.rand(1, 22).', Mumbai, India',
                    'emergency_contact_name' => 'Emergency Contact',
                    'emergency_contact_phone' => '90000'.rand(10000, 99999),
                    'bank_name' => 'HDFC Bank',
                    'bank_account_last4' => (string) rand(1000, 9999),
                    'salary' => $salary,
                ]
            );
        }

        Department::where('code', 'HR')->update(['manager_user_id' => $usersByEmail['priya.nair@peopleops.test']->id]);
        Department::where('code', 'ENG')->update(['manager_user_id' => $usersByEmail['vikram.singh@peopleops.test']->id]);
        Department::where('code', 'FIN')->update(['manager_user_id' => $usersByEmail['neha.iyer@peopleops.test']->id]);
        Department::where('code', 'SAL')->update(['manager_user_id' => $usersByEmail['sneha.kapoor@peopleops.test']->id]);
        Department::where('code', 'CS')->update(['manager_user_id' => $usersByEmail['meera.joshi@peopleops.test']->id]);
    }

    private function seedLeaves(): void
    {
        foreach ([
            ['Earned Leave', 'EL', 18, true],
            ['Sick Leave', 'SL', 10, true],
            ['Casual Leave', 'CL', 8, true],
            ['Unpaid Leave', 'UL', 0, false],
        ] as [$name, $code, $quota, $paid]) {
            LeaveType::updateOrCreate(['code' => $code], [
                'name' => $name,
                'annual_quota' => $quota,
                'is_paid' => $paid,
                'requires_approval' => true,
                'status' => true,
            ]);
        }

        $users = User::pluck('id', 'email');
        $types = LeaveType::pluck('id', 'code');
        $approver = $users['priya.nair@peopleops.test'];

        foreach ([
            ['dev.patel@peopleops.test', 'SL', '2026-05-16', '2026-05-20', 'Medical recovery leave', 'approved'],
            ['arjun.menon@peopleops.test', 'EL', '2026-05-23', '2026-05-27', 'Family travel', 'pending'],
            ['aisha.khan@peopleops.test', 'CL', '2026-05-29', '2026-05-29', 'Personal appointment', 'pending'],
            ['kabir.shah@peopleops.test', 'UL', '2026-06-03', '2026-06-04', 'Relocation work', 'rejected'],
        ] as [$email, $type, $start, $end, $reason, $status]) {
            LeaveRequest::updateOrCreate(
                ['user_id' => $users[$email], 'start_date' => $start, 'end_date' => $end],
                [
                    'leave_type_id' => $types[$type],
                    'total_days' => \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($end)) + 1,
                    'reason' => $reason,
                    'status' => $status,
                    'approved_by_user_id' => $status === 'pending' ? null : $approver,
                    'approved_at' => $status === 'pending' ? null : now()->subDays(2),
                    'rejection_reason' => $status === 'rejected' ? 'Please resubmit with manager confirmation.' : null,
                ]
            );
        }
    }

    private function seedAttendance(): void
    {
        $users = User::whereIn('status', ['active', 'probation', 'on_leave'])->get();

        foreach ($users as $user) {
            for ($day = 0; $day < 7; $day++) {
                $date = now()->subDays($day);

                if ($date->isWeekend()) {
                    continue;
                }

                $isOnLeave = $user->status === 'on_leave' && $day <= 2;
                $clockIn = $isOnLeave ? null : $date->copy()->setTime(9, rand(12, 58));
                $clockOut = $isOnLeave || $day === 0 ? null : $date->copy()->setTime(18, rand(0, 45));

                AttendanceRecord::updateOrCreate(
                    ['user_id' => $user->id, 'work_date' => $date->toDateString()],
                    [
                        'clock_in_at' => $clockIn,
                        'clock_out_at' => $clockOut,
                        'status' => $isOnLeave ? 'leave' : ($clockIn && $clockIn->format('H:i') > '09:45' ? 'late' : 'present'),
                        'notes' => $isOnLeave ? 'Approved sick leave.' : null,
                    ]
                );
            }
        }
    }

    private function seedPayroll(): void
    {
        $periods = [now()->subMonth()->format('F Y'), now()->format('F Y')];

        User::with('employeeProfile')->where('status', '!=', 'inactive')->get()->each(function (User $user) use ($periods) {
            foreach ($periods as $period) {
                $basic = (float) ($user->employeeProfile?->salary ?? 0);
                $allowances = round($basic * 0.12, 2);
                $deductions = round($basic * 0.035, 2);
                $tax = round($basic * 0.08, 2);

                Payroll::updateOrCreate(
                    ['user_id' => $user->id, 'pay_period' => $period],
                    [
                        'basic_salary' => $basic,
                        'allowances' => $allowances,
                        'deductions' => $deductions,
                        'tax' => $tax,
                        'net_pay' => $basic + $allowances - $deductions - $tax,
                        'payment_date' => $period === now()->subMonth()->format('F Y') ? now()->subMonth()->endOfMonth()->toDateString() : null,
                        'status' => $period === now()->subMonth()->format('F Y') ? 'paid' : 'processed',
                    ]
                );
            }
        });
    }

    private function seedAnnouncements(): void
    {
        $publisher = User::where('email', 'priya.nair@peopleops.test')->first();
        $engineering = Department::where('code', 'ENG')->first();

        foreach ([
            ['May payroll is processing', 'Payroll for May 2026 is under review and will be released after finance approval.', 'all', null],
            ['Hybrid work update', 'Engineering and Customer Success teams will follow the Tuesday-Thursday office schedule from next week.', 'department', $engineering?->id],
            ['Leave planning reminder', 'Please submit planned leave at least five working days in advance to help teams plan coverage.', 'all', null],
        ] as [$title, $body, $audience, $departmentId]) {
            Announcement::updateOrCreate(
                ['title' => $title],
                [
                    'body' => $body,
                    'audience' => $audience,
                    'department_id' => $departmentId,
                    'published_by_user_id' => $publisher?->id,
                    'published_at' => now()->subDays(rand(1, 8)),
                    'status' => 'published',
                ]
            );
        }
    }

    private function seedHolidaysAndAssets(): void
    {
        foreach ([
            ['Independence Day', '2026-08-15', 'National'],
            ['Diwali', '2026-11-08', 'Festival'],
            ['Christmas', '2026-12-25', 'National'],
            ['Company Foundation Day', '2026-09-12', 'Company'],
        ] as [$name, $date, $type]) {
            DB::table('holidays')->updateOrInsert(['name' => $name, 'holiday_date' => $date], [
                'type' => $type,
                'description' => $type.' holiday',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $assignees = User::pluck('id', 'email');
        foreach ([
            ['LAP-1001', 'MacBook Pro 14', 'Laptop', 'rohan.mehta@peopleops.test'],
            ['LAP-1002', 'Dell Latitude 7440', 'Laptop', 'aisha.khan@peopleops.test'],
            ['MON-2201', 'Dell 27 Monitor', 'Monitor', 'vikram.singh@peopleops.test'],
            ['PHN-3101', 'iPhone 15', 'Phone', 'priya.nair@peopleops.test'],
        ] as [$tag, $name, $category, $email]) {
            DB::table('assets')->updateOrInsert(['asset_tag' => $tag], [
                'name' => $name,
                'category' => $category,
                'assigned_to_user_id' => $assignees[$email] ?? null,
                'assigned_at' => now()->subMonths(rand(1, 9))->toDateString(),
                'condition' => 'Good',
                'status' => 'assigned',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
