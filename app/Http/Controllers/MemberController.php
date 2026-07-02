<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $scoped = User::where('group_id', $request->user()->group_id);

        return view('anggota', [
            'group' => $request->user()->group,
            'members' => (clone $scoped)->active()
                ->orderByRaw("FIELD(role, 'Koordinator', 'Sekretaris', 'Bendahara', 'Anggota')")
                ->orderBy('name')
                ->get(),
            'pending' => (clone $scoped)->where('status', 'pending')->orderBy('created_at')->get(),
        ]);
    }

    public function approve(Request $request, User $member): RedirectResponse
    {
        Gate::authorize('manage-members');
        abort_unless($member->group_id === $request->user()->group_id, 404);

        $member->update(['status' => 'active']);

        return redirect()->route('anggota.index')->with('ok', $member->name.' disetujui menjadi anggota.');
    }

    public function reject(Request $request, User $member): RedirectResponse
    {
        Gate::authorize('manage-members');
        abort_unless($member->group_id === $request->user()->group_id, 404);
        abort_unless($member->status === 'pending', 400);

        $member->delete();

        return redirect()->route('anggota.index')->with('ok', 'Permintaan bergabung ditolak.');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-members');

        $data = $this->validated($request);
        $data['password'] = $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ])['password'];
        $data['join_date'] ??= today();
        $data['group_id'] = $request->user()->group_id;

        User::create($data);

        return redirect()->route('anggota.index')->with('ok', 'Anggota ditambahkan. Ia bisa langsung login.');
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        Gate::authorize('manage-members');
        abort_unless($member->group_id === $request->user()->group_id, 404);

        $data = $this->validated($request, $member);

        if ($request->filled('password')) {
            $data['password'] = $request->validate([
                'password' => ['string', 'min:6'],
            ])['password'];
        }

        $member->update($data);

        return redirect()->route('anggota.index')->with('ok', 'Data anggota diperbarui.');
    }

    public function destroy(Request $request, User $member): RedirectResponse
    {
        Gate::authorize('manage-members');
        abort_unless($member->group_id === $request->user()->group_id, 404);

        if ($member->id === $request->user()->id) {
            return back()->withErrors(['member' => 'Tidak bisa menghapus akun sendiri.']);
        }

        $member->delete();

        return redirect()->route('anggota.index')->with('ok', 'Anggota dihapus.');
    }

    private function validated(Request $request, ?User $member = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($member)],
            'role' => ['required', Rule::in(User::ROLES)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'join_date' => ['nullable', 'date'],
        ]);
    }
}
