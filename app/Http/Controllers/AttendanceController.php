<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class AttendanceController extends Controller
{
    public function markAttendance( $value ) {
        if($value == 1) {
            DB::table('attendance')->insert([
                'employee_id' => Auth::id(),
                'clock_in_date' => date('d-m-Y', time()),
                'clock_in_time' => date('h:i:s', time())
            ]);
        } else {
            DB::table('attendance')->where('employee_id', Auth::id())->where('clock_in_date', date('d-m-Y', time()))->update([
                'clock_out_time' => date('h:i:s', time())
            ]);
        }
        return back();
    }
}
