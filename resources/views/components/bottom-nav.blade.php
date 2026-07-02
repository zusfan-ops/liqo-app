@php
$items = [
    ['route' => 'beranda', 'match' => 'beranda', 'label' => 'Beranda', 'icon' => 'home'],
    ['route' => 'jadwal.index', 'match' => 'jadwal.*', 'label' => 'Jadwal', 'icon' => 'calendar-days'],
    ['route' => 'anggota.index', 'match' => 'anggota.*', 'label' => 'Anggota', 'icon' => 'users'],
    ['route' => 'keuangan.index', 'match' => 'keuangan.*', 'label' => 'Kas', 'icon' => 'wallet'],
    ['route' => 'menu', 'match' => 'menu', 'label' => 'Menu', 'icon' => 'layout-grid'],
];
@endphp
<nav class="fixed inset-x-0 bottom-0 z-40 mx-auto max-w-md border-t border-pine-100 bg-surface/95 backdrop-blur"
     style="padding-bottom: var(--safe-bottom)">
    <ul class="grid grid-cols-5">
        @foreach ($items as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <li>
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-1 py-2.5 transition"
                   @if ($active) aria-current="page" @endif>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl transition {{ $active ? 'bg-pine-500 text-white shadow-soft' : 'text-ink-faint' }}">
                        <x-icon :name="$item['icon']" />
                    </span>
                    <span class="text-[0.65rem] font-semibold {{ $active ? 'text-pine-600' : 'text-ink-faint' }}">
                        {{ $item['label'] }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
