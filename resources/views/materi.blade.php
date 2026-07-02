<x-layout title="Materi">
    <div x-data="{ open: false }">
        <x-page-header title="Materi Kajian" subtitle="Catatan & resume kajian" />

        <div class="space-y-3 px-5 py-5">
            @forelse ($notes as $n)
                <div class="card p-4" x-data="{ expand: false }">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="font-display font-semibold text-ink">{{ $n->title }}</h3>
                            <p class="mt-0.5 text-xs text-ink-faint">
                                {{ $n->date->translatedFormat('l, j F Y') }}@if ($n->speaker) · {{ $n->speaker }}@endif
                            </p>
                        </div>
                        @can('manage-notes')
                            <form method="POST" action="{{ route('materi.destroy', $n) }}"
                                  onsubmit="return confirm('Hapus materi ini?')">
                                @csrf @method('DELETE')
                                <button class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-500">
                                    <x-icon name="trash" size="15" />
                                </button>
                            </form>
                        @endcan
                    </div>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-ink-soft"
                       :class="expand ? '' : 'line-clamp-3'">{{ $n->content }}</p>
                    <button type="button" class="mt-2 text-xs font-semibold text-pine-500" @click="expand = !expand"
                            x-text="expand ? 'Tutup' : 'Baca selengkapnya'"></button>
                </div>
            @empty
                <x-empty-state icon="book-open" title="Belum ada materi" desc="Simpan ringkasan dan catatan kajian agar bisa dibaca kembali." />
            @endforelse
        </div>

        @can('manage-notes')
            <x-fab label="Tulis" @click="open = true" />

            <x-sheet title="Materi Baru">
                <form method="POST" action="{{ route('materi.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label">Judul Materi</label>
                        <input class="field" name="title" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label">Tanggal</label>
                            <input class="field" type="date" name="date" value="{{ today()->toDateString() }}" required>
                        </div>
                        <div>
                            <label class="label">Pemateri</label>
                            <input class="field" name="speaker" placeholder="Ustadzah…">
                        </div>
                    </div>
                    <div>
                        <label class="label">Isi / Ringkasan</label>
                        <textarea class="field" name="content" rows="7" required
                                  placeholder="Poin-poin penting kajian…"></textarea>
                    </div>
                    <button class="btn-primary w-full">Simpan Materi</button>
                </form>
            </x-sheet>
        @endcan
    </div>
</x-layout>
