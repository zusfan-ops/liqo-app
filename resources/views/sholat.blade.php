@php
    $iconMap = [
        'Fajr' => 'cloud',
        'Sunrise' => 'sunrise',
        'Dhuhr' => 'sun',
        'Asr' => 'sun',
        'Maghrib' => 'sunset',
        'Isha' => 'moon',
    ];
@endphp
<x-layout title="Jadwal Sholat">
    <div x-data="{ picking: false }">
        <x-page-header title="Jadwal Sholat" subtitle="Metode Kemenag RI · via Aladhan" />

        <div class="space-y-5 px-5 py-5">
            {{-- City / hijri --}}
            <div class="motif-pine rounded-3xl p-5 text-white shadow-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="eyebrow text-pine-100">Kota</h2>
                        <p class="mt-1 flex items-center gap-1.5 font-display text-2xl font-semibold">
                            <x-icon name="map-pin" size="20" class="text-gold-400" /> {{ $group->city }}
                        </p>
                        @if ($prayer && $prayer['hijri'])
                            <p class="mt-1 text-sm text-pine-100">{{ now()->translatedFormat('l, j F Y') }} · {{ $prayer['hijri'] }}</p>
                        @endif
                    </div>
                    @can('manage-settings')
                        <button type="button" @click="picking = !picking"
                                class="rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold">Ganti</button>
                    @endcan
                </div>
            </div>

            @can('manage-settings')
                <div x-show="picking" x-cloak x-transition class="card p-4">
                    <h2 class="eyebrow mb-3">Pilih Kota</h2>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ($cities as $c)
                            <form method="POST" action="{{ route('sholat.city') }}">
                                @csrf
                                <input type="hidden" name="city" value="{{ $c }}">
                                <button class="flex w-full items-center justify-center gap-1 rounded-lg py-2 text-sm font-semibold transition {{ $group->city === $c ? 'bg-pine-500 text-white' : 'bg-base text-ink-soft' }}">
                                    @if ($group->city === $c)
                                        <x-icon name="check" size="13" />
                                    @endif
                                    {{ $c }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endcan

            {{-- Timings --}}
            @if ($prayer)
                <div class="card divide-y divide-pine-100/60 overflow-hidden">
                    @foreach ($labels as $key => $label)
                        @php $isNext = $nextPrayer && $nextPrayer['label'] === $label && $key !== 'Sunrise'; @endphp
                        <div class="flex items-center justify-between px-5 py-3.5 {{ $isNext ? 'bg-pine-50' : '' }}">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $isNext ? 'bg-pine-500 text-white' : 'bg-base text-ink-soft' }}">
                                    <x-icon :name="$iconMap[$key]" size="18" />
                                </span>
                                <span class="font-semibold {{ $isNext ? 'text-pine-600' : 'text-ink' }}">{{ $label }}</span>
                                @if ($isNext)
                                    <span class="chip bg-pine-500 text-white">Berikutnya</span>
                                @endif
                            </div>
                            <span class="font-display text-lg font-semibold {{ $isNext ? 'text-pine-600' : 'text-ink' }}">
                                {{ $prayer['timings'][$key] ?? '—' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card p-6 text-center text-sm text-ink-soft">
                    Jadwal sholat tidak dapat dimuat. Periksa koneksi internet lalu muat ulang halaman.
                </div>
            @endif
        </div>
    </div>
</x-layout>

