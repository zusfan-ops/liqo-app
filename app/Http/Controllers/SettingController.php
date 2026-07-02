<?php

namespace App\Http\Controllers;

use App\Services\PrayerTimes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pengaturan', [
            'group' => $request->user()->group,
            'cities' => PrayerTimes::CITIES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        Gate::authorize('manage-settings');

        $request->user()->group->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', Rule::in(PrayerTimes::CITIES)],
            'tilawah_target' => ['required', 'integer', 'min:1', 'max:100'],
        ]));

        return redirect()->route('pengaturan.edit')->with('ok', 'Pengaturan disimpan.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();
        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        $user->update(['password' => $data['new_password']]);

        return redirect()->route('pengaturan.edit')->with('ok', 'Kata sandi berhasil diganti.');
    }
}
