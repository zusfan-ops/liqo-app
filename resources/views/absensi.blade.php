@php
    $statusStyle = [
        'hadir' => 'bg-pine-500 text-white',
        'izin' => 'bg-gold-500 text-white',
        'sakit' => 'bg-rose-400 text-white',
        'alpa' => 'bg-ink-soft text-white',
    ];
    $counts = collect($statusStyle)->map(fn ($v, $k) => $records->filter(fn ($s) => $s === $k)->count());
    $terisi = $records->count();
    $persen = $members->count() > 0 ? round(($counts['hadir'] / $members->count()) * 100) : 0;
@endphp
<x-layout title="Absensi">
    <x-page-header title="Absensi" subtitle="Kehadiran per pertemuan" />

    <div class="space-y-5 px-5 py-5">
        @if ($meetings->isEmpty())
            <x-empty-state icon="clipboard-check" title="Belum ada pertemuan"
                           desc="Tambahkan kegiatan di halaman Jadwal terlebih dahulu." />
        @else
            {{-- Meeting picker --}}
            <form method="GET" action="{{ route('absensi.index') }}">
                <label class="label">Pilih Pertemuan</label>
                <div class="relative">
                    <select name="meeting" class="field appearance-none pr-10" onchange="this.form.submit()">
                        @foreach ($meetings as $m)
                            <option value="{{ $m->id }}" @selected($meeting && $m->id === $meeting->id)>
                                {{ $m->date->translatedFormat('j M Y') }} — {{ $m->title }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-ink-faint">
                        <x-icon name="chevron-down" size="18" />
                    </span>
                </div>
            </form>

            @if ($meeting)
                {{-- Summary --}}
                <div class="card p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="eyebrow">Kehadiran</h2>
                            <p class="mt-1 font-display text-2xl font-bold text-pine-600">{{ $persen }}%</p>
                        </div>
                        <div class="flex gap-2 text-center text-xs">
                            @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa'] as $k => $lbl)
                                <div class="rounded-xl bg-base px-3 py-2">
                                    <p class="font-bold text-ink">{{ $counts[$k] }}</p>
                                    <p class="text-ink-faint">{{ $lbl }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-pine-50">
                        <div class="h-full rounded-full bg-pine-500 transition-all" style="width: {{ $persen }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-ink-faint">{{ $terisi }} dari {{ $members->count() }} anggota tercatat.</p>
                </div>

                {{-- Member list --}}
                <section class="space-y-3">
                    @foreach ($members as $u)
                        @php $current = $records[$u->id] ?? null; @endphp
                        <div class="card p-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pine-50 font-bold text-pine-500">
                                    {{ $u->initials() }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-ink">{{ $u->name }}</p>
                                    <p class="text-xs text-ink-faint">{{ $u->role }}</p>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-4 gap-2">
                                @foreach (['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa'] as $k => $lbl)
                                    @can('manage-attendance')
                                        <form method="POST" action="{{ route('absensi.set') }}">
                                            @csrf
                                            <input type="hidden" name="meeting_id" value="{{ $meeting->id }}">
                                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                                            <input type="hidden" name="status" value="{{ $k }}">
                                            <button class="w-full rounded-lg py-2 text-xs font-semibold transition {{ $current === $k ? $statusStyle[$k] : 'bg-base text-ink-soft' }}">
                                                {{ $lbl }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="rounded-lg py-2 text-center text-xs font-semibold {{ $current === $k ? $statusStyle[$k] : 'bg-base text-ink-faint' }}">
                                            {{ $lbl }}
                                        </span>
                                    @endcan
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </section>
            @endif
        @endif
    </div>
</x-layout>
