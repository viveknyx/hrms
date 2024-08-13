<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;

class UserController extends Controller
{
    public function users() {
        return view('users');
    }

    public function roles() {
        return view('roles');
    }

    public function addNewRole() {
        return view('add-new-role');
    }

    public function addNewUser() {
        return view('add-new-user');
    }

    public function changePassword($id) {
        $data['user'] = DB::table('users')->where('id', $id)->first();
        return view('change-password', $data);
    }

    public function editRole($id) {
        $data['role'] = DB::table('roles')->where('id', $id)->first();
        return view('edit-role', $data);
    }

    public function changePermission($id) {
        $data['user'] = DB::table('users')->where('id', $id)->first();
        return view('change-permission', $data);
    }

    public function editUser($id) {
        $data['user'] = DB::table('users')->where('id', $id)->first();
        return view('edit-user', $data);
    }

    public function addRole(Request $req) {
        $roleName = $req->input('roleName');

        $projectsArray = $req->input('projects', []);
        $projectCategoryArray = $req->input('projectCategory', []);
        $projectTypeArray = $req->input('projectType', []);

        $allSelectedCheckboxes = array_merge($projectsArray, $projectCategoryArray, $projectTypeArray);

        $selectedCheckboxesString = implode(',', $allSelectedCheckboxes);

        if($allSelectedCheckboxes == '' || $allSelectedCheckboxes == null) {
            echo"<script>
                    alert('Please select atleast one role!');
                    window.history.back();
                </script>";
        } else if($roleName == '' || $roleName == null) {
            echo"<script>
                    alert('Please enter Role Name!');
                    window.history.back();
                </script>";
        } else {
            DB::table('roles')->insert([
                'name' => $roleName,
                'permissions' => $selectedCheckboxesString,
                'status' => 1
            ]);

            return back();
        }

    }

    public function updateRole(Request $req, $id) {
        $roleName = $req->input('roleName');
        $projectsArray = $req->input('projects', []);
        $projectCategoryArray = $req->input('projectCategory', []);
        $projectTypeArray = $req->input('projectType', []);

        $allSelectedCheckboxes = array_merge($projectsArray, $projectCategoryArray, $projectTypeArray);
        $selectedCheckboxesString = implode(',', $allSelectedCheckboxes);

        if($allSelectedCheckboxes == '' || $allSelectedCheckboxes == null) {
            echo"<script>
                    alert('Please select atleast one role!');
                    window.history.back();
                </script>";
        } else if($roleName == '' || $roleName == null) {
            echo"<script>
                    alert('Please enter Role Name!');
                    window.history.back();
                </script>";
        } else {
            DB::table('roles')->where('id', $id)->update([
                'name' => $roleName,
                'permissions' => $selectedCheckboxesString
            ]);

            DB::table('users')->where('role', $id)->update([
                'permissions' => $selectedCheckboxesString
            ]);

            return back();
        }
    }

    public function updatePermission(Request $req, $id) {
        //input fields
        $projectsArray = $req->input('projects', []);
        $projectCategoryArray = $req->input('projectCategory', []);
        $projectTypeArray = $req->input('projectType', []);

        //all Selected Checkboxes Array
        $allSelectedCheckboxes = array_merge($projectsArray, $projectCategoryArray, $projectTypeArray);
        //all Selected Checkboxes String
        $allPermissionString = implode(',', $allSelectedCheckboxes);

        if($allSelectedCheckboxes == '' || $allSelectedCheckboxes == null) {
            echo"<script>
                    alert('Please select atleast one role!');
                    window.history.back();
                </script>";
        } else {
            DB::table('users')->where('id', $id)->update([
                'permissions' => $allPermissionString
            ]);

            return back();
        }
    }

    public function addUser(Request $req) {
        $username = $req->input('username');
        $email = $req->input('email');
        $role = $req->input('role');
        $password = $req->input('password');
        $confirmPassword = $req->input('confirmPassword');
        $permissions = DB::table('roles')->where('id', $role)->pluck('permissions')->first();

        if($password == $confirmPassword && $username != null && $username!= '' && $email != null && $email!= '' && $role != null && $role!= '') {
            DB::table('users')->insert([
                'name' => $username,
                'email' => $email,
                'role' => $role,
                'permissions' => $permissions,
                'password' => Hash::make($password),
                'status' => 1,
                'group_id' => 0
            ]);

            return back();
        } else if ($password != $confirmPassword) {
            echo"<script>
                    alert('Passwords do not match!');
                    window.history.back();
                </script>";
        } else {
            echo"<script>
                    alert('All fields are required!');
                    window.history.back();
                </script>";
        }
    }

    public function updatePassword(Request $req, $id) {
        $newpassword = $req->input('newpassword');
        $confirmpassword = $req->input('confirmpassword');

        if ($newpassword == $confirmpassword && ($newpassword != '' || $newpassword != null) && ($confirmpassword != '' || $confirmpassword != null)) {
            DB::table('users')->where('id', $id)->update([
                'password' => Hash::make($newpassword)
            ]);

            return back();
        } elseif ($newpassword != $confirmpassword) {
            // Passwords do not match
            echo "<script>
                    alert('Passwords do not match!');
                    window.history.back();
                  </script>";
        } else {
            // New password is empty or null
            echo "<script>
                    alert('Password Field is required!');
                    window.history.back();
                  </script>";
        }
    }

    public function updateUser(Request $req, $id) {
        $username = $req->input('username');
        $email = $req->input('email');
        $role = $req->input('role');

        $permissions = DB::table('roles')->where('id', $role)->pluck('permissions')->first();

        DB::table('users')->where('id', $id)->update([
            'name' => $username,
            'email' => $email,
            'role' => $role,
            'permissions' => $permissions
        ]);

        return back();
    }

    // return response()->json([
    //     'msg' => 'working'
    // ]);
}
