<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $groupId = $request->user()->group_id;
        $meetings = Meeting::where('group_id', $groupId)->orderByDesc('date')->orderByDesc('time')->get();
        $meeting = $request->filled('meeting')
            ? $meetings->firstWhere('id', (int) $request->query('meeting'))
            : $meetings->firstWhere(fn (Meeting $m) => $m->date->lte(today())) ?? $meetings->first();

        $records = $meeting
            ? Attendance::where('meeting_id', $meeting->id)->pluck('status', 'user_id')
            : collect();

        return view('absensi', [
            'meetings' => $meetings,
            'meeting' => $meeting,
            'members' => User::where('group_id', $groupId)->active()->orderBy('name')->get(),
            'records' => $records,
        ]);
    }

    public function set(Request $request): RedirectResponse
    {
        Gate::authorize('manage-attendance');

        $data = $request->validate([
            'meeting_id' => ['required', 'exists:meetings,id'],
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', Rule::in(Attendance::STATUSES)],
        ]);

        $groupId = $request->user()->group_id;
        abort_unless(Meeting::find($data['meeting_id'])->group_id === $groupId, 404);
        abort_unless(User::find($data['user_id'])->group_id === $groupId, 404);

        Attendance::updateOrCreate(
            ['meeting_id' => $data['meeting_id'], 'user_id' => $data['user_id']],
            ['status' => $data['status']]
        );

        return redirect()->route('absensi.index', ['meeting' => $data['meeting_id']]);
    }
}
