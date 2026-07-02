@php
    $maxPages = max(1, $days->max('pages'), $target);
    $weekTotal = $days->sum('pages');
    $progress = min(100, round(($todayPages / max(1, $target)) * 100));
@endphp
<x-layout title="Tilawah">
    <div x-data="{ open: false }">
        <x-page-header title="Tilawah" subtitle="Catatan pribadi {{ explode(' ', auth()->user()->name)[0] }}" />

        <div class="space-y-5 px-5 py-5">
            {{-- Today's progress --}}
            <div class="motif-pine rounded-3xl p-5 text-white shadow-lift">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="eyebrow text-pine-100">Hari Ini</h2>
                        <p class="mt-1 font-display text-3xl font-bold">
                            {{ $todayPages }}<span class="text-base font-medium text-pine-100"> / {{ $target }} halaman</span>
                        </p>
                    </div>
                    <div class="flex flex-col items-center rounded-2xl bg-white/10 px-4 py-2.5">
                        <x-icon name="flame" size="22" class="text-gold-400" />
                        <p class="mt-1 text-lg font-bold leading-none">{{ $streak }}</p>
                        <p class="text-[0.65rem] text-pine-100">hari beruntun</p>
                    </div>
                </div>
                <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-white/15">
                    <div class="h-full rounded-full bg-gold-400 transition-all" style="width: {{ $progress }}%"></div>
                </div>
                <p class="mt-2 text-xs text-pine-100">
                    {{ $todayPages >= $target ? 'Masya Allah, target hari ini tercapai! 🌟' : 'Semangat, sedikit lagi mencapai target!' }}
                </p>
            </div>

            {{-- 7-day chart --}}
            <div class="card p-4">
                <div class="flex items-center justify-between">
                    <h2 class="eyebrow">7 Hari Terakhir</h2>
                    <span class="text-xs font-semibold text-ink-faint">{{ $weekTotal }} halaman</span>
                </div>
                <div class="mt-4 flex h-28 items-end justify-between gap-2">
                    @foreach ($days as $d)
                        @php $hPct = round(($d['pages'] / $maxPages) * 100); @endphp
                        <div class="flex flex-1 flex-col items-center gap-1.5">
                            <span class="text-[0.65rem] font-semibold text-ink-soft">{{ $d['pages'] ?: '' }}</span>
                            <div class="flex h-20 w-full items-end rounded-lg bg-base">
                                <div class="w-full rounded-lg {{ $d['pages'] >= $target ? 'bg-pine-500' : 'bg-pine-200' }} transition-all"
                                     style="height: {{ max($d['pages'] > 0 ? 8 : 0, $hPct) }}%"></div>
                            </div>
                            <span class="text-[0.65rem] {{ $d['date']->isToday() ? 'font-bold text-pine-600' : 'text-ink-faint' }}">
                                {{ $d['date']->translatedFormat('D') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Entries --}}
            <section>
                <h2 class="eyebrow mb-3">Riwayat</h2>
                @forelse ($entries as $e)
                    <div class="card mb-3 flex items-center gap-3 p-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pine-50 text-pine-500">
                            <x-icon name="book-marked" size="18" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-ink">{{ $e->pages }} halaman @if ($e->surah) · {{ $e->surah }}@endif</p>
                            <p class="truncate text-xs text-ink-faint">
                                {{ $e->date->translatedFormat('l, j M Y') }}@if ($e->note) · {{ $e->note }}@endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('tilawah.destroy', $e) }}"
                              onsubmit="return confirm('Hapus catatan ini?')">
                            @csrf @method('DELETE')
                            <button class="flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint hover:bg-rose-100 hover:text-rose-500">
                                <x-icon name="trash" size="15" />
                            </button>
                        </form>
                    </div>
                @empty
                    <x-empty-state icon="book-marked" title="Belum ada catatan" desc="Catat tilawah harianmu dan jaga semangat istiqamah." />
                @endforelse
            </section>
        </div>

        <x-fab label="Catat" @click="open = true" />

        <x-sheet title="Catat Tilawah">
            <form method="POST" action="{{ route('tilawah.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">Tanggal</label>
                        <input class="field" type="date" name="date" value="{{ today()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="label">Halaman</label>
                        <input class="field" type="number" name="pages" min="1" max="604" placeholder="4" required>
                    </div>
                </div>
                <div>
                    <label class="label">Surah</label>
                    <input class="field" name="surah" placeholder="Al-Baqarah">
                </div>
                <div>
                    <label class="label">Catatan</label>
                    <input class="field" name="note" placeholder="Ba'da subuh…">
                </div>
                <button class="btn-primary w-full">Simpan</button>
            </form>
        </x-sheet>
    </div>
</x-layout>
