<x-layout title="Pengaturan">
    <x-page-header title="Pengaturan" subtitle="Majelis & akun" back="{{ route('menu') }}" />

    <div class="space-y-5 px-5 py-5">
        @can('manage-settings')
            <div class="card flex items-center justify-between p-4">
                <div>
                    <h2 class="eyebrow">Kode Undangan Grup</h2>
                    <p class="mt-1 font-display text-2xl font-bold tracking-[0.25em] text-pine-600">{{ $group->code }}</p>
                </div>
                <a href="{{ route('anggota.index') }}" class="text-xs font-semibold text-pine-500">Undang anggota</a>
            </div>

            <section class="card p-5">
                <h2 class="eyebrow mb-4">Profil Majelis</h2>
                <form method="POST" action="{{ route('pengaturan.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="label">Nama Majelis</label>
                        <input class="field" name="name" value="{{ old('name', $group->name) }}" required>
                    </div>
                    <div>
                        <label class="label">Kota (jadwal sholat)</label>
                        <div class="relative">
                            <select class="field appearance-none pr-10" name="city">
                                @foreach ($cities as $c)
                                    <option value="{{ $c }}" @selected($group->city === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-ink-faint">
                                <x-icon name="chevron-down" size="18" />
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="label">Target Tilawah Harian (halaman)</label>
                        <input class="field" type="number" name="tilawah_target" min="1" max="100"
                               value="{{ old('tilawah_target', $group->tilawah_target) }}" required>
                    </div>
                    <button class="btn-primary w-full">Simpan Pengaturan</button>
                </form>
            </section>
        @endcan

        <section class="card p-5">
            <h2 class="eyebrow mb-1 flex items-center gap-1.5">
                <x-icon name="key" size="14" /> Ganti Kata Sandi
            </h2>
            <p class="mb-4 text-xs text-ink-faint">Untuk akun {{ auth()->user()->email }}</p>
            <form method="POST" action="{{ route('pengaturan.sandi') }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="label">Kata Sandi Saat Ini</label>
                    <input class="field" type="password" name="current_password" required>
                </div>
                <div>
                    <label class="label">Kata Sandi Baru</label>
                    <input class="field" type="password" name="new_password" minlength="6" required>
                </div>
                <div>
                    <label class="label">Ulangi Kata Sandi Baru</label>
                    <input class="field" type="password" name="new_password_confirmation" minlength="6" required>
                </div>
                <button class="btn-ghost w-full">Ganti Kata Sandi</button>
            </form>
        </section>

        <p class="text-center text-xs text-ink-faint">
            Grup: {{ $group->name }} · Data terpusat di MySQL,<br>semua anggota grup melihat data yang sama.
        </p>
    </div>
</x-layout>
