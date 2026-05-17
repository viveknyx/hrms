<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('announcements.index', [
            'announcements' => Announcement::with(['department', 'publisher'])
                ->latest('published_at')
                ->paginate(10),
            'departments' => Department::where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:2000'],
            'audience' => ['required', 'in:all,department,managers'],
            'department_id' => ['nullable', 'required_if:audience,department', 'exists:departments,id'],
            'status' => ['required', 'in:draft,published'],
        ]);

        Announcement::create($data + [
            'published_by_user_id' => $request->user()->id,
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        return back()->with('success', 'Announcement saved.');
    }
}
