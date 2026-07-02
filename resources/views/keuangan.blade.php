<x-layout title="Kas">
    <div x-data="{ open: false }">
        <x-page-header title="Kas Majelis" subtitle="Pemasukan & pengeluaran" />

        <div class="space-y-5 px-5 py-5">
            {{-- Balance card --}}
            <div class="motif-pine rounded-3xl p-5 text-white shadow-lift">
                <h2 class="eyebrow text-pine-100">Saldo Kas</h2>
                <p class="mt-1 font-display text-3xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</p>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-white/10 p-3">
                        <p class="flex items-center gap-1 text-xs text-pine-100">
                            <x-icon name="arrow-down" size="13" class="text-gold-400" /> Pemasukan
                        </p>
                        <p class="mt-1 font-semibold">Rp {{ number_format($totalIn, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-3">
                        <p class="flex items-center gap-1 text-xs text-pine-100">
                            <x-icon name="arrow-up" size="13" class="text-rose-300" /> Pengeluaran
                        </p>
                        <p class="mt-1 font-semibold">Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Entries --}}
            <section>
                <h2 class="eyebrow mb-3">Riwayat Transaksi</h2>
                @forelse ($entries as $e)
                    <div class="card mb-3 flex items-center gap-3 p-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $e->type === 'masuk' ? 'bg-pine-50 text-pine-500' : 'bg-rose-100 text-rose-500' }}">
                            <x-icon :name="$e->type === 'masuk' ? 'arrow-down' : 'arrow-up'" size="18" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-ink">{{ $e->category }}</p>
                            <p class="truncate text-xs text-ink-faint">
                                {{ $e->date->translatedFormat('j M Y') }}@if ($e->note) · {{ $e->note }}@endif
                            </p>
                        </div>
                        <p class="shrink-0 font-semibold {{ $e->type === 'masuk' ? 'text-pine-600' : 'text-rose-500' }}">
                            {{ $e->type === 'masuk' ? '+' : '−' }}{{ number_format($e->amount, 0, ',', '.') }}
                        </p>
                        @can('manage-finance')
                            <form method="POST" action="{{ route('keuangan.destroy', $e) }}"
                                  onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button class="flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint hover:bg-rose-100 hover:text-rose-500">
                                    <x-icon name="trash" size="15" />
                                </button>
                            </form>
                        @endcan
                    </div>
                @empty
                    <x-empty-state icon="wallet" title="Belum ada transaksi" desc="Catat pemasukan dan pengeluaran kas majelis di sini." />
                @endforelse
            </section>
        </div>

        @can('manage-finance')
            <x-fab label="Catat" @click="open = true" />

            <x-sheet title="Catat Transaksi">
                <form method="POST" action="{{ route('keuangan.store') }}" class="space-y-4" x-data="{ type: 'masuk' }">
                    @csrf
                    <input type="hidden" name="type" :value="type">
                    <div class="grid grid-cols-2 gap-2 rounded-xl bg-base p-1.5">
                        <button type="button" @click="type = 'masuk'"
                                class="rounded-lg py-2.5 text-sm font-semibold transition"
                                :class="type === 'masuk' ? 'bg-pine-500 text-white shadow-soft' : 'text-ink-soft'">Pemasukan</button>
                        <button type="button" @click="type = 'keluar'"
                                class="rounded-lg py-2.5 text-sm font-semibold transition"
                                :class="type === 'keluar' ? 'bg-rose-500 text-white shadow-soft' : 'text-ink-soft'">Pengeluaran</button>
                    </div>
                    <div>
                        <label class="label">Tanggal</label>
                        <input class="field" type="date" name="date" value="{{ today()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="label">Kategori</label>
                        <input class="field" name="category" placeholder="Iuran Bulanan / Infaq / Konsumsi…" required>
                    </div>
                    <div>
                        <label class="label">Jumlah (Rp)</label>
                        <input class="field" type="number" name="amount" min="1" step="1" placeholder="50000" required>
                    </div>
                    <div>
                        <label class="label">Catatan</label>
                        <input class="field" name="note" placeholder="Keterangan singkat">
                    </div>
                    <button class="btn-primary w-full">Simpan Transaksi</button>
                </form>
            </x-sheet>
        @endcan
    </div>
</x-layout>
