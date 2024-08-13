<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ActivityController extends Controller
{
    public function activity() {
        return view('activity');
    }

    public function addNewActivity() {
        return view('add-new-activity');
    }

    public function editActivity($id) {
        $data['activity'] = DB::table('activity')->where('id', $id)->first();
        return view('edit-activity', $data);
    }

    public function addActivity(Request $req) {
        $title = $req->input('title');
        $startDate = $req->input('startDate');
        $endDate = $req->input('endDate');

        DB::table('activity')->insert([
            'title' => $title,
            'start_date' => $startDate,
            'end_date' => $endDate,
            // 'created_by_user_id' => auth()->user()->id,
        ]);

        return back();
    }

    public function removeActivity($id) {
        DB::table('activity')->where('id', $id)->delete();

        return back();
    }

    public function updateActivity(Request $req, $id) {
        $title = $req->input('edittitle');
        $startDate = $req->input('startDate');
        $endDate = $req->input('endDate');

        DB::table('activity')->where('id', $id)->update([
            'title' => $title,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return back();
    }
}
