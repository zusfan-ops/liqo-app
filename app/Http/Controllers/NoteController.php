<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        return view('materi', [
            'notes' => Note::where('group_id', $request->user()->group_id)->orderByDesc('date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-notes');

        Note::create($request->validate([
            'date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]) + ['group_id' => $request->user()->group_id]);

        return redirect()->route('materi.index')->with('ok', 'Materi disimpan.');
    }

    public function destroy(Request $request, Note $materi): RedirectResponse
    {
        Gate::authorize('manage-notes');
        abort_unless($materi->group_id === $request->user()->group_id, 404);
        $materi->delete();

        return redirect()->route('materi.index')->with('ok', 'Materi dihapus.');
    }
}
