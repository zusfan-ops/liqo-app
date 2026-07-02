@php
    $h = now()->hour;
    $salam = $h < 11 ? 'Selamat pagi' : ($h < 15 ? 'Selamat siang' : ($h < 18 ? 'Selamat sore' : 'Selamat malam'));
    $shortcuts = [
        ['route' => 'absensi.index', 'label' => 'Kehadiran', 'icon' => 'clipboard-check', 'tone' => 'bg-pine-50 text-pine-500'],
        ['route' => 'materi.index', 'label' => 'Materi', 'icon' => 'book-open', 'tone' => 'bg-rose-100 text-rose-500'],
        ['route' => 'pengumuman.index', 'label' => 'Pengumuman', 'icon' => 'megaphone', 'tone' => 'bg-gold-400/15 text-gold-600'],
        ['route' => 'tilawah.index', 'label' => 'Tilawah', 'icon' => 'book-marked', 'tone' => 'bg-pine-50 text-pine-500'],
        ['route' => 'sholat.index', 'label' => 'Sholat', 'icon' => 'clock', 'tone' => 'bg-rose-100 text-rose-500'],
        ['route' => 'doa', 'label' => 'Doa', 'icon' => 'heart-handshake', 'tone' => 'bg-gold-400/15 text-gold-600'],
    ];
@endphp
<x-layout>
    {{-- Hero header --}}
    <header class="motif-pine rounded-b-3xl px-5 pb-8 pt-6 text-white shadow-lift">
        <p class="font-arabic text-lg text-gold-400">السَّلَامُ عَلَيْكُمْ</p>
        <p class="mt-1 text-sm text-pine-100">{{ $salam }}, {{ explode(' ', auth()->user()->name)[0] }} 🌸</p>
        <h1 class="mt-1 font-display text-[1.7rem] font-semibold leading-tight">{{ $group->name }}</h1>

        <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-pine-100">
            <span class="rounded-full bg-white/10 px-3 py-1">{{ now()->translatedFormat('l, j F Y') }}</span>
            @if ($prayer && $prayer['hijri'])
                <span class="rounded-full bg-white/10 px-3 py-1">{{ $prayer['hijri'] }}</span>
            @endif
        </div>

        {{-- Next prayer strip --}}
        <a href="{{ route('sholat.index') }}" class="mt-4 flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
            <div class="flex items-center gap-2">
                <x-icon name="clock" size="18" class="text-gold-400" />
                <span class="text-sm">{{ $nextPrayer ? $nextPrayer['label'].' berikutnya' : 'Jadwal sholat' }}</span>
            </div>
            <span class="font-display text-lg font-semibold">{{ $nextPrayer['time'] ?? '—' }}</span>
        </a>
    </header>

    <div class="space-y-6 px-5 py-6">
        @if ($pendingCount > 0)
            <a href="{{ route('anggota.index') }}"
               class="card flex items-center gap-3 border-gold-400/50 bg-gold-400/[0.08] p-4 animate-fade-up">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gold-400/20 text-gold-600">
                    <x-icon name="user-plus" size="20" />
                </span>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-ink">{{ $pendingCount }} permintaan bergabung</p>
                    <p class="text-xs text-ink-soft">Ketuk untuk menyetujui atau menolak.</p>
                </div>
                <x-icon name="chevron-right" size="18" class="text-gold-600" />
            </a>
        @endif

        {{-- Next activity --}}
        <section class="animate-fade-up">
            <div class="mb-2 flex items-center justify-between">
                <h2 class="eyebrow">Kegiatan Berikutnya</h2>
                <a href="{{ route('jadwal.index') }}" class="text-xs font-semibold text-pine-500">Semua jadwal</a>
            </div>
            @if ($upcoming)
                <a href="{{ route('jadwal.index') }}" class="card block overflow-hidden">
                    <div class="flex">
                        <div class="flex w-20 flex-col items-center justify-center bg-pine-500 py-4 text-white">
                            <span class="text-3xl font-bold leading-none">{{ $upcoming->date->day }}</span>
                            <span class="mt-1 text-xs uppercase tracking-wide">{{ $upcoming->date->translatedFormat('M') }}</span>
                        </div>
                        <div class="min-w-0 flex-1 p-4">
                            <span class="chip bg-rose-100 text-rose-500">
                                @php $diff = (int) today()->diffInDays($upcoming->date, false); @endphp
                                {{ $diff === 0 ? 'Hari ini' : ($diff === 1 ? 'Besok' : $diff.' hari lagi') }} · {{ $upcoming->time }}
                            </span>
                            <h3 class="mt-2 truncate font-display text-lg font-semibold text-ink">{{ $upcoming->title }}</h3>
                            <p class="mt-1 flex items-center gap-1 text-sm text-ink-soft">
                                <x-icon name="map-pin" size="14" /> {{ $upcoming->location }}
                            </p>
                        </div>
                    </div>
                </a>
            @else
                <div class="card p-6 text-center text-sm text-ink-soft">Belum ada kegiatan terjadwal.</div>
            @endif
        </section>

        {{-- Shortcuts --}}
        <section>
            <h2 class="eyebrow mb-3">Menu Cepat</h2>
            <div class="grid grid-cols-3 gap-3">
                @foreach ($shortcuts as $s)
                    <a href="{{ route($s['route']) }}" class="card flex flex-col items-center gap-2 py-4 transition active:scale-95">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $s['tone'] }}">
                            <x-icon :name="$s['icon']" size="22" />
                        </span>
                        <span class="text-xs font-semibold text-ink-soft">{{ $s['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Kas + Announcement --}}
        <section class="grid gap-4">
            <a href="{{ route('keuangan.index') }}" class="card flex items-center justify-between p-4">
                <div>
                    <h2 class="eyebrow">Saldo Kas Majelis</h2>
                    <p class="mt-1 font-display text-2xl font-bold text-pine-600">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-pine-50 text-pine-500">
                    <x-icon name="chevron-right" size="22" />
                </span>
            </a>

            @if ($pinned)
                <a href="{{ route('pengumuman.index') }}" class="card border-gold-400/40 bg-gold-400/[0.06] p-4">
                    <div class="flex items-center gap-2">
                        <x-icon name="sparkles" size="16" class="text-gold-600" />
                        <h2 class="eyebrow text-gold-600">Pengumuman</h2>
                    </div>
                    <h3 class="mt-1.5 font-semibold text-ink">{{ $pinned->title }}</h3>
                    <p class="mt-1 line-clamp-2 text-sm text-ink-soft">{{ $pinned->body }}</p>
                </a>
            @endif
        </section>

        <p class="pt-2 text-center text-xs text-ink-faint">Ruang Ukhuwah · Pendamping majelis ibu-ibu</p>
    </div>
</x-layout>
