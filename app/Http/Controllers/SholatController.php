<?php

namespace App\Http\Controllers;

use App\Services\PrayerTimes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SholatController extends Controller
{
    public function index(Request $request): View
    {
        $group = $request->user()->group;
        $prayer = PrayerTimes::today($group->city, $group->country);

        return view('sholat', [
            'group' => $group,
            'prayer' => $prayer,
            'nextPrayer' => $prayer ? PrayerTimes::next($prayer['timings']) : null,
            'cities' => PrayerTimes::CITIES,
            'labels' => PrayerTimes::LABELS,
        ]);
    }

    public function setCity(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $data = $request->validate(['city' => ['required', Rule::in(PrayerTimes::CITIES)]]);
        $request->user()->group->update(['city' => $data['city']]);

        return redirect()->route('sholat.index');
    }
}
