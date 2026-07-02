<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(Request $request): View
    {
        $scoped = Meeting::where('group_id', $request->user()->group_id);

        return view('jadwal', [
            'upcoming' => (clone $scoped)->whereDate('date', '>=', today())->orderBy('date')->orderBy('time')->get(),
            'past' => (clone $scoped)->whereDate('date', '<', today())->orderByDesc('date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-meetings');
        Meeting::create($this->validated($request) + ['group_id' => $request->user()->group_id]);

        return redirect()->route('jadwal.index')->with('ok', 'Kegiatan ditambahkan.');
    }

    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        Gate::authorize('manage-meetings');
        abort_unless($meeting->group_id === $request->user()->group_id, 404);
        $meeting->update($this->validated($request));

        return redirect()->route('jadwal.index')->with('ok', 'Kegiatan diperbarui.');
    }

    public function destroy(Request $request, Meeting $meeting): RedirectResponse
    {
        Gate::authorize('manage-meetings');
        abort_unless($meeting->group_id === $request->user()->group_id, 404);
        $meeting->delete();

        return redirect()->route('jadwal.index')->with('ok', 'Kegiatan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'location' => ['required', 'string', 'max:255'],
            'host' => ['nullable', 'string', 'max:255'],
            'topic' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ]);
    }
}
