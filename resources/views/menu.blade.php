@php
    $items = [
        ['route' => 'jadwal.index', 'label' => 'Jadwal', 'icon' => 'calendar-days', 'tone' => 'bg-pine-50 text-pine-500'],
        ['route' => 'absensi.index', 'label' => 'Absensi', 'icon' => 'clipboard-check', 'tone' => 'bg-rose-100 text-rose-500'],
        ['route' => 'anggota.index', 'label' => 'Anggota', 'icon' => 'users', 'tone' => 'bg-gold-400/15 text-gold-600'],
        ['route' => 'keuangan.index', 'label' => 'Kas', 'icon' => 'wallet', 'tone' => 'bg-pine-50 text-pine-500'],
        ['route' => 'pengumuman.index', 'label' => 'Pengumuman', 'icon' => 'megaphone', 'tone' => 'bg-rose-100 text-rose-500'],
        ['route' => 'materi.index', 'label' => 'Materi', 'icon' => 'book-open', 'tone' => 'bg-gold-400/15 text-gold-600'],
        ['route' => 'tilawah.index', 'label' => 'Tilawah', 'icon' => 'book-marked', 'tone' => 'bg-pine-50 text-pine-500'],
        ['route' => 'sholat.index', 'label' => 'Sholat', 'icon' => 'clock', 'tone' => 'bg-rose-100 text-rose-500'],
        ['route' => 'doa', 'label' => 'Doa', 'icon' => 'heart-handshake', 'tone' => 'bg-gold-400/15 text-gold-600'],
        ['route' => 'pengaturan.edit', 'label' => 'Pengaturan', 'icon' => 'settings', 'tone' => 'bg-pine-50 text-pine-500'],
    ];
@endphp
<x-layout title="Menu">
    <x-page-header title="Menu" subtitle="Semua fitur Ruang Ukhuwah" />

    <div class="space-y-5 px-5 py-5">
        {{-- Profile --}}
        <div class="card flex items-center gap-4 p-4">
            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-pine-500 font-display text-xl font-bold text-white">
                {{ auth()->user()->initials() }}
            </span>
            <div class="min-w-0 flex-1">
                <p class="truncate font-display font-semibold text-ink">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-ink-faint">{{ auth()->user()->email }}</p>
                <span class="chip mt-1 bg-pine-50 text-pine-600">{{ auth()->user()->role }}</span>
            </div>
        </div>

        {{-- Feature grid --}}
        <div class="grid grid-cols-3 gap-3">
            @foreach ($items as $item)
                <a href="{{ route($item['route']) }}" class="card flex flex-col items-center gap-2 py-4 transition active:scale-95">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $item['tone'] }}">
                        <x-icon :name="$item['icon']" size="22" />
                    </span>
                    <span class="text-xs font-semibold text-ink-soft">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-rose w-full">
                <x-icon name="log-out" size="18" /> Keluar
            </button>
        </form>

        <p class="text-center text-xs text-ink-faint">Ruang Ukhuwah · Laravel + MySQL</p>
    </div>
</x-layout>
