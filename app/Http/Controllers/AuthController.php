<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Services\PrayerTimes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('beranda'));
    }

    public function showRegister(): View
    {
        return view('auth.register', [
            'groups' => Group::orderBy('name')->get(['id', 'name']),
            'cities' => PrayerTimes::CITIES,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:30'],
            'register_as' => ['required', Rule::in(['koordinator', 'anggota'])],
            // koordinator: buat grup baru
            'group_name' => ['required_if:register_as,koordinator', 'nullable', 'string', 'max:255'],
            'city' => ['nullable', Rule::in(PrayerTimes::CITIES)],
            // anggota: gabung grup yang sudah ada
            'join_by' => ['required_if:register_as,anggota', 'nullable', Rule::in(['list', 'code'])],
            'group_id' => ['nullable', 'exists:groups,id'],
            'group_code' => ['nullable', 'string', 'max:8'],
        ]);

        $user = DB::transaction(function () use ($data) {
            if ($data['register_as'] === 'koordinator') {
                $group = Group::create([
                    'name' => $data['group_name'],
                    'code' => Group::generateCode(),
                    'city' => $data['city'] ?? 'Bandung',
                ]);
                $role = 'Koordinator';
                $status = 'active';
            } else {
                $group = null;
                $role = 'Anggota';
                if (($data['join_by'] ?? null) === 'code') {
                    $group = Group::where('code', strtoupper(trim($data['group_code'] ?? '')))->first();
                    if (! $group) {
                        throw ValidationException::withMessages(['group_code' => 'Kode grup tidak ditemukan. Periksa kembali kode dari koordinator Anda.']);
                    }
                    $status = 'active'; // punya kode = sudah diundang
                } else {
                    if (! empty($data['group_id'])) {
                        $group = Group::find($data['group_id']);
                    }
                    if (! $group) {
                        throw ValidationException::withMessages(['group_id' => 'Pilih grup majelis atau masukkan kode grup.']);
                    }
                    $status = 'pending'; // tanpa kode = tunggu persetujuan koordinator
                }
            }

            return User::create([
                'group_id' => $group->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $role,
                'status' => $status,
                'phone' => $data['phone'] ?? null,
                'join_date' => today(),
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();

        if (! $user->isActive()) {
            return redirect()->route('menunggu');
        }

        return redirect()->route('beranda')->with('ok',
            $user->isKoordinator()
                ? 'Grup berhasil dibuat! Bagikan kode '.$user->group->code.' untuk mengundang anggota.'
                : 'Selamat datang di '.$user->group->name.'!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
