<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('roles.index', [
            'roles' => Role::orderBy('name')->get(),
            'permissionGroups' => Role::availablePermissions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['array'],
            'permissions.*' => [Rule::in(Role::allPermissionKeys())],
            'status' => ['required', 'boolean'],
        ]);

        Role::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'permissions' => $data['permissions'] ?? [],
            'status' => $data['status'],
            'is_system' => false,
        ]);

        return back()->with('success', 'Role created.');
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('roles', 'name')->ignore($role->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['array'],
            'permissions.*' => [Rule::in(Role::allPermissionKeys())],
            'status' => ['required', 'boolean'],
        ]);

        $role->update([
            'name' => $data['name'],
            'slug' => $role->is_system ? $role->slug : Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'permissions' => $role->slug === 'super-admin' ? Role::allPermissionKeys() : ($data['permissions'] ?? []),
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Role updated.');
    }
}
