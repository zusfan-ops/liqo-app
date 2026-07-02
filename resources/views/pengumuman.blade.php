<x-layout title="Pengumuman">
    <div x-data="{ open: false }">
        <x-page-header title="Pengumuman" subtitle="Info untuk seluruh anggota" />

        <div class="space-y-3 px-5 py-5">
            @forelse ($announcements as $a)
                <div class="card p-4 {{ $a->pinned ? 'border-gold-400/50 bg-gold-400/[0.06]' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            @if ($a->pinned)
                                <span class="chip mb-1.5 bg-gold-400/20 text-gold-600">
                                    <x-icon name="pin" size="12" /> Disematkan
                                </span>
                            @endif
                            <h3 class="font-display font-semibold text-ink">{{ $a->title }}</h3>
                            <p class="mt-0.5 text-xs text-ink-faint">{{ $a->created_at->translatedFormat('l, j F Y · H.i') }}</p>
                        </div>
                        @can('manage-announcements')
                            <div class="flex shrink-0 gap-1.5">
                                <form method="POST" action="{{ route('pengumuman.pin', $a) }}">
                                    @csrf
                                    <button class="flex h-8 w-8 items-center justify-center rounded-lg {{ $a->pinned ? 'bg-gold-400/20 text-gold-600' : 'bg-base text-ink-faint' }}"
                                            title="{{ $a->pinned ? 'Lepas sematan' : 'Sematkan' }}">
                                        <x-icon name="pin" size="15" />
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('pengumuman.destroy', $a) }}"
                                      onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-500">
                                        <x-icon name="trash" size="15" />
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-ink-soft">{{ $a->body }}</p>
                </div>
            @empty
                <x-empty-state icon="megaphone" title="Belum ada pengumuman" desc="Pengumuman untuk seluruh anggota majelis akan tampil di sini." />
            @endforelse
        </div>

        @can('manage-announcements')
            <x-fab label="Buat" @click="open = true" />

            <x-sheet title="Pengumuman Baru">
                <form method="POST" action="{{ route('pengumuman.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label">Judul</label>
                        <input class="field" name="title" required>
                    </div>
                    <div>
                        <label class="label">Isi Pengumuman</label>
                        <textarea class="field" name="body" rows="5" required
                                  placeholder="Assalamu'alaikum ibu-ibu…"></textarea>
                    </div>
                    <button class="btn-primary w-full">Umumkan</button>
                </form>
            </x-sheet>
        @endcan
    </div>
</x-layout>
