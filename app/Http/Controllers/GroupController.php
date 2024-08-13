<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GroupController extends Controller
{
    public function groups() {
        return view('groups');
    }

    public function viewGroup($id) {
        $data['group'] = DB::table('groups')->where('id', $id)->first();
        return view('view-group', $data);
    }

    public function addNewGroup() {
        return view('add-new-group');
    }

    public function addNewMember($id) {
        $data['group'] = DB::table('groups')->where('id', $id)->first();
        return view('add-new-member', $data);
    }

    public function editGroup($id) {
        $data['group'] = DB::table('groups')->where('id', $id)->first();
        return view('edit-group', $data);
    }

    public function assignProject() {
        return view('assign-project');
    }

    public function assignTask() {
        return view('assign-task');
    }

    public function assignProjectDB(Request $req) {
        $projectType = $req->input('projectType');
        $projectName = $req->input('projectname');
        $groups = $req->input('groups', []);
        $startdate = $req->input('startdate');
        $enddate = $req->input('enddate');

        $groupsString = implode(',', $groups);

        DB::table('assign_projects')->insert([
            'project_type' => $projectType,
            'project_name' => $projectName,
            'group_id' => $groupsString,
            'start_date' => $startdate,
            'end_date' => $enddate
        ]);

        return back();

    }

    public function assignTaskDB(Request $req) {
        $projectCategory = $req->input('projectCategory');
        $taskName = $req->input('taskName');
        $groups = $req->input('groups', []);
        $editor1 = $req->input('editor1');
        $files = $req->input('fileAttachments', []);

        $groupsString = implode(',', $groups);

        DB::table('assign_tasks')->insert([
            'task_name' => $taskName,
            'project_category' => $projectCategory,
            'description' => $editor1,
            'group_id' => $groupsString,
            'files' => $files,
        ]);

        return back();
    }

    public function scheduleSessionDB(Request $req) {
        $sessionName = $req->input('sessionName');
        $groups = $req->input('groups', []);
        $joiningDetails = $req->input('editor1');
        $startDate = $req->input('startDate');
        $time = $req->input('time');
        $sessionDuration = $req->input('sessionDuration');

        $groupsString = implode(',', $groups);

        if (empty($sessionName) || empty($groups) || empty($joiningDetails) || empty($startDate) || empty($time) || empty($sessionDuration)) {
            echo "<script>
                    alert('All fields are neccessary!');
                    window.history.back();
                  </script>";
        } else {
            DB::table('schedule_sessions')->insert([
                'name' => $sessionName,
                'duration' => $sessionDuration,
                'group_id' => $groupsString,
                'joining_detail' => $joiningDetails,
                'start_date' => $startDate,
                'time' => $time,
            ]);

            return back();
        }

    }

    public function sendGroupMessageDB(Request $req) {
        $messageTitle = $req->input('messageTitle');
        $groups = $req->input('groups', []);
        $description = $req->input('editor1');
        $files = $req->input('fileAttachments', []);

        $groupsString = implode('::', $groups);

        if (empty($messageTitle) || empty($groups) || empty($description)) {
            echo "<script>
                    alert('All fields are neccessary!');
                    window.history.back();
                  </script>";
        } else {
            DB::table('group_messages')->insert([
                'name' => $messageTitle,
                'group_id' => $groupsString,
                'description' => $description,
                'files' => $files
            ]);

            return back();
        }
    }

    public function scheduleSession() {
        return view('schedule-session');
    }

    public function sendGroupMessage() {
        return view('send-group-message');
    }

    public function addGroup(Request $req) {
        $groupname = $req->input('groupname');
        $evaluator = $req->input('evaluator');

        if($groupname == '' || $groupname == null) {
            echo "<script>
                    alert('Group Name can not be empty!');
                    window.history.back();
                  </script>";
        } else {
            DB::table('groups')->insert([
                'name' => $groupname,
                'evaluator_id' => $evaluator
            ]);

            return back();
        }
    }

    public function addMember($id, Request $req) {
        $users = $req->input('users', []);

        foreach ($users as $user) {
            DB::table('users')->where('id', $user)->update([
                'group_id' => DB::raw("CONCAT_WS(',', group_id, $id)")
            ]);
        }

        return back();
    }


    public function removeMember($groupId, $id) {
        DB::table('users')->where('id', $id)->where('group_id', 'like', "%$groupId%")->update([
            'group_id' => DB::raw("REPLACE(group_id, '$groupId', '')")
        ]);

        return back();
    }

    public function updateGroup($id, Request $req) {
        $newgroupname = $req->input('newgroupname');

        DB::table('groups')->where('id', $id)->update([
            'name' => $newgroupname
        ]);

        return back();
    }

}
