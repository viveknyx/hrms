<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_system',
        'status',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system' => 'boolean',
        'status' => 'boolean',
    ];

    public static function availablePermissions(): array
    {
        return [
            'Workspace' => [
                'dashboard.view' => 'View dashboard',
                'announcements.view' => 'View announcements',
            ],
            'People' => [
                'employees.view' => 'View employees',
                'employees.manage' => 'Create and update employees',
                'departments.manage' => 'Manage departments and designations',
            ],
            'Time Off' => [
                'attendance.view' => 'View attendance',
                'attendance.manage' => 'Manage attendance for everyone',
                'leave.view' => 'View leave requests',
                'leave.manage' => 'Approve and reject leave',
            ],
            'Finance' => [
                'payroll.view' => 'View payroll',
                'payroll.manage' => 'Create and process payroll',
            ],
            'Admin' => [
                'announcements.manage' => 'Publish announcements',
                'roles.manage' => 'Manage roles and permissions',
                'settings.manage' => 'Manage HRMS settings',
            ],
        ];
    }

    public static function allPermissionKeys(): array
    {
        return collect(self::availablePermissions())
            ->flatMap(fn (array $group) => array_keys($group))
            ->values()
            ->all();
    }
}
