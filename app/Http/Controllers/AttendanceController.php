<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $canManage = $user->hasPermission('attendance.manage');

        $records = AttendanceRecord::with('user.employeeProfile.department')
            ->when(! $canManage, fn ($query) => $query->where('user_id', $user->id))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->user_id))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('work_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('work_date', '<=', $request->to))
            ->latest('work_date')
            ->paginate(20)
            ->withQueryString();

        return view('attendance.index', [
            'records' => $records,
            'todayRecord' => AttendanceRecord::where('user_id', $user->id)->whereDate('work_date', today())->first(),
            'employees' => $canManage ? User::where('status', 'active')->orderBy('name')->get() : collect(),
            'canManage' => $canManage,
        ]);
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $now = now();
        $record = AttendanceRecord::firstOrCreate(
            ['user_id' => $request->user()->id, 'work_date' => $now->toDateString()],
            ['status' => $now->format('H:i') > '10:00' ? 'late' : 'present']
        );

        if (! $record->clock_in_at) {
            $record->update(['clock_in_at' => $now]);
        }

        return back()->with('success', 'Clock-in recorded.');
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $record = AttendanceRecord::where('user_id', $request->user()->id)
            ->whereDate('work_date', today())
            ->first();

        if (! $record || ! $record->clock_in_at) {
            return back()->with('error', 'Clock in before clocking out.');
        }

        $record->update(['clock_out_at' => now()]);

        return back()->with('success', 'Clock-out recorded.');
    }

    public function update(Request $request, AttendanceRecord $attendance): RedirectResponse
    {
        $attendance->update($request->validate([
            'status' => ['required', 'in:present,late,half_day,remote,absent,leave'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]));

        return back()->with('success', 'Attendance record updated.');
    }

    public function markAttendance(int $value): RedirectResponse
    {
        return $value === 1 ? $this->clockIn(request()) : $this->clockOut(request());
    }
}
