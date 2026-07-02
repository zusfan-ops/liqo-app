<?php

namespace App\Http\Controllers;

use App\Models\TilawahEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TilawahController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $entries = $user->tilawahEntries()->orderByDesc('date')->orderByDesc('id')->get();
        $target = $user->group->tilawah_target;

        // Halaman per hari, 7 hari terakhir (untuk grafik batang)
        $days = collect(range(6, 0))->map(function ($back) use ($entries) {
            $date = today()->subDays($back);

            return [
                'date' => $date,
                'pages' => (int) $entries->filter(fn ($e) => $e->date->isSameDay($date))->sum('pages'),
            ];
        });

        // Streak: hari berturut-turut (mundur dari hari ini) dengan tilawah
        $streak = 0;
        $cursor = today();
        $dates = $entries->map(fn ($e) => $e->date->toDateString())->unique();
        if (! $dates->contains($cursor->toDateString())) {
            $cursor = $cursor->subDay(); // hari ini belum, hitung dari kemarin
        }
        while ($dates->contains($cursor->toDateString())) {
            $streak++;
            $cursor = $cursor->subDay();
        }

        return view('tilawah', [
            'entries' => $entries,
            'target' => $target,
            'days' => $days,
            'todayPages' => $days->last()['pages'],
            'streak' => $streak,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'pages' => ['required', 'integer', 'min:1', 'max:604'],
            'surah' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->tilawahEntries()->create($data);

        return redirect()->route('tilawah.index')->with('ok', 'Tilawah dicatat. Barakallahu fiik!');
    }

    public function destroy(Request $request, TilawahEntry $tilawah): RedirectResponse
    {
        abort_unless($tilawah->user_id === $request->user()->id, 403);
        $tilawah->delete();

        return redirect()->route('tilawah.index')->with('ok', 'Catatan dihapus.');
    }
}
