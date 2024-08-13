<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ProjectController extends Controller
{
    public function projects()
    {
        return view('projects');
    }

    public function addNewProject()
    {
        return view('add-new-project');
    }

    public function editProject($id)
    {
        $data['project'] = DB::table('projects')->where('id', $id)->first();
        return view('edit-project', $data);
    }

    public function viewProject($id)
    {
        $data['project'] = DB::table('projects')->where('id', $id)->first();
        return view('view-project', $data);
    }

    public function categories()
    {
        return view('categories');
    }

    public function projectTypes()
    {
        return view('project-types');
    }

    public function addProjectType(Request $req)
    {
        $data = $req->input('addProjectTypeField');

        DB::table('project_types')->insert([
            'name' => $data,
            'status' => 1
        ]);

        return back();
    }

    public function addProjectCategory(Request $req)
    {
        $data = $req->input('addProjectCategoryField');

        DB::table('project_categories')->insert([
            'name' => $data,
            'status' => 1
        ]);

        return back();
    }

    public function editProjectCategory(Request $req)
    {
        $inputId = $req->input('idcom');
        $inputName = $req->input('namecom');

        DB::table('project_categories')->where('id', $inputId)->update([
            'name' => $inputName
        ]);

        return back();
    }

    public function editProjecttype(Request $req)
    {
        $inputId = $req->input('idcom');
        $inputName = $req->input('namecom');

        DB::table('project_types')->where('id', $inputId)->update([
            'name' => $inputName
        ]);

        return back();
    }

    public function addProject(Request $req)
    {
        $projectName = $req->input('projectName');
        $projectType = $req->input('projectType');
        $projectNumber = $req->input('projectNumber');
        $projectCategory = $req->input('projectCategory');
        $editor1 = $req->input('editor1');

        DB::table('projects')->insert([
            'name' => $projectName,
            'category' => $projectCategory,
            'type' => $projectType,
            'project_number' => $projectNumber,
            'description' => $editor1,
            'files' => implode("::", $req->projectLinks)
        ]);

        return back();
    }

    public function updateProject(Request $req, $id)
    {
        $projectName = $req->input('projectName');
        $projectType = $req->input('projectType');
        $projectNumber = $req->input('projectNumber');
        $projectCategory = $req->input('projectCategory');
        $editor1 = $req->input('editor1');

        DB::table('projects')->where('id', $id)->update([
            'name' => $projectName,
            'category' => $projectCategory,
            'type' => $projectType,
            'project_number' => $projectNumber,
            'description' => $editor1,
            'files' => implode("::", $req->projectLinks)
        ]);

        return back();
    }

    public function changeStatus($type, $value, $id)
    {
        //type 1 = projects table, 2 = category table, 3 = types table, 4 = roles table
        //value 1 = active, 0 = inactive
        if ($type == 1) {
            DB::table('projects')->where('id', $id)->update(['status' => $value]);
        } else if ($type == 2) {
            DB::table('project_categories')->where('id', $id)->update(['status' => $value]);
        } else if ($type == 3) {
            DB::table('project_types')->where('id', $id)->update(['status' => $value]);
        } else if ($type == 4) {
            DB::table('roles')->where('id', $id)->update(['status' => $value]);
        } else if ($type == 5) {
            DB::table('users')->where('id', $id)->update(['status' => $value]);
        }
        return back();
    }

    public function uploadFiles(Request $req, $id)
    {
        $solution = $req->input('solution');
        $fileAttachments = $req->file('fileAttachments', []);
        $links = $req->input('projectLinks', []);    

        if ($req->hasFile('fileAttachments')) {
            foreach ($req->file('fileAttachments') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('project_files'), $fileName);
            }
        }

        $filePathName = [];
        foreach ($fileAttachments as $file) {
            $filePathName[] = 'project_files' . '/' . $file->getClientOriginalName();
        }

        DB::table('projects_submitted')->insert([
            'student_id' => auth()->user()->id,
            'project_id' => $id,
            'solution' => $solution,
            'files' => implode(",", $filePathName),
            'links' => implode(",", $links)
        ]);

        return back();
    }
}
