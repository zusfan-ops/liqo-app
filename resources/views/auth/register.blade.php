<x-layout title="Daftar" :nav="false">
    <div class="motif-pine min-h-dvh px-6 py-10">
        <div class="mb-6 text-center text-white">
            <p class="font-arabic text-2xl text-gold-400">السَّلَامُ عَلَيْكُمْ</p>
            <h1 class="mt-2 font-display text-3xl font-semibold">Buat Akun</h1>
            <p class="mt-1 text-sm text-pine-100">Gabung atau dirikan majelis Anda</p>
        </div>

        <div class="card animate-fade-up p-6" x-data="{ as: '{{ old('register_as', 'anggota') }}', joinBy: '{{ old('join_by', 'code') }}' }">
            <form method="POST" action="{{ route('register.attempt') }}" class="space-y-4">
                @csrf

                {{-- Pilih peran --}}
                <div>
                    <label class="label">Daftar sebagai</label>
                    <input type="hidden" name="register_as" :value="as">
                    <div class="grid grid-cols-2 gap-2 rounded-xl bg-base p-1.5">
                        <button type="button" @click="as = 'anggota'"
                                class="rounded-lg py-2.5 text-sm font-semibold transition"
                                :class="as === 'anggota' ? 'bg-pine-500 text-white shadow-soft' : 'text-ink-soft'">Anggota</button>
                        <button type="button" @click="as = 'koordinator'"
                                class="rounded-lg py-2.5 text-sm font-semibold transition"
                                :class="as === 'koordinator' ? 'bg-pine-500 text-white shadow-soft' : 'text-ink-soft'">Koordinator</button>
                    </div>
                    <p class="mt-1.5 text-xs text-ink-faint"
                       x-text="as === 'koordinator'
                            ? 'Anda akan membuat grup majelis baru dan mendapat kode undangan untuk anggota.'
                            : 'Anda akan bergabung ke grup majelis yang sudah ada.'"></p>
                </div>

                {{-- Data diri --}}
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input class="field" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="label">Email</label>
                    <input class="field" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="label">No. HP / WhatsApp</label>
                    <input class="field" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Kata Sandi</label>
                        <input class="field" type="password" name="password" minlength="6" required>
                    </div>
                    <div>
                        <label class="label">Ulangi Sandi</label>
                        <input class="field" type="password" name="password_confirmation" minlength="6" required>
                    </div>
                </div>

                {{-- Koordinator: buat grup baru --}}
                <div x-show="as === 'koordinator'" x-cloak class="space-y-4 rounded-2xl bg-pine-50 p-4">
                    <div>
                        <label class="label">Nama Majelis / Grup Baru</label>
                        <input class="field" name="group_name" value="{{ old('group_name') }}"
                               placeholder="Majelis Ta'lim …" :required="as === 'koordinator'">
                    </div>
                    <div>
                        <label class="label">Kota (untuk jadwal sholat)</label>
                        <div class="relative">
                            <select class="field appearance-none pr-10" name="city">
                                @foreach ($cities as $c)
                                    <option value="{{ $c }}" @selected(old('city', 'Bandung') === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-ink-faint">
                                <x-icon name="chevron-down" size="18" />
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Anggota: gabung grup --}}
                <div x-show="as === 'anggota'" x-cloak class="space-y-4 rounded-2xl bg-pine-50 p-4">
                    <input type="hidden" name="join_by" :value="joinBy">
                    <div class="grid grid-cols-2 gap-2 rounded-xl bg-white p-1.5">
                        <button type="button" @click="joinBy = 'code'"
                                class="rounded-lg py-2 text-xs font-semibold transition"
                                :class="joinBy === 'code' ? 'bg-pine-500 text-white' : 'text-ink-soft'">Pakai Kode Grup</button>
                        <button type="button" @click="joinBy = 'list'"
                                class="rounded-lg py-2 text-xs font-semibold transition"
                                :class="joinBy === 'list' ? 'bg-pine-500 text-white' : 'text-ink-soft'">Pilih dari Daftar</button>
                    </div>

                    <div x-show="joinBy === 'code'">
                        <label class="label">Kode Grup dari Koordinator</label>
                        <input class="field text-center font-display text-lg font-bold uppercase tracking-[0.3em]"
                               name="group_code" value="{{ old('group_code') }}" maxlength="8" placeholder="A2B3C4"
                               :required="as === 'anggota' && joinBy === 'code'">
                    </div>

                    <div x-show="joinBy === 'list'" x-cloak>
                        <label class="label">Pilih Grup Majelis</label>
                        <p class="mb-2 text-xs text-ink-faint">Tanpa kode, keanggotaan Anda menunggu persetujuan koordinator terlebih dahulu.</p>
                        @if ($groups->isEmpty())
                            <p class="text-sm text-ink-soft">Belum ada grup terdaftar. Minta kode dari koordinator, atau daftar sebagai Koordinator untuk membuat grup baru.</p>
                        @else
                            <div class="relative">
                                <select class="field appearance-none pr-10" name="group_id">
                                    <option value="">— pilih grup —</option>
                                    @foreach ($groups as $g)
                                        <option value="{{ $g->id }}" @selected(old('group_id') == $g->id)>{{ $g->name }}</option>
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-ink-faint">
                                    <x-icon name="chevron-down" size="18" />
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full"
                        x-text="as === 'koordinator' ? 'Daftar & Buat Grup' : 'Daftar & Gabung'"></button>
            </form>

            <p class="mt-4 text-center text-sm text-ink-soft">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-pine-500">Masuk</a>
            </p>
        </div>
    </div>
</x-layout>
