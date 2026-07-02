<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        return view('pengumuman', [
            'announcements' => Announcement::where('group_id', $request->user()->group_id)
                ->orderByDesc('pinned')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-announcements');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);
        $data['user_id'] = $request->user()->id;
        $data['group_id'] = $request->user()->group_id;

        Announcement::create($data);

        return redirect()->route('pengumuman.index')->with('ok', 'Pengumuman dibuat.');
    }

    public function togglePin(Request $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('manage-announcements');
        abort_unless($announcement->group_id === $request->user()->group_id, 404);
        $announcement->update(['pinned' => ! $announcement->pinned]);

        return redirect()->route('pengumuman.index');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        Gate::authorize('manage-announcements');
        abort_unless($announcement->group_id === $request->user()->group_id, 404);
        $announcement->delete();

        return redirect()->route('pengumuman.index')->with('ok', 'Pengumuman dihapus.');
    }
}
