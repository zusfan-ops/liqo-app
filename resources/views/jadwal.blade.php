<x-layout title="Jadwal">
    <div x-data="{
            open: false,
            mode: 'add',
            action: '{{ route('jadwal.store') }}',
            form: { title: '', date: '', time: '09:00', location: '', host: '', topic: '', note: '' },
            openAdd() {
                this.mode = 'add';
                this.action = '{{ route('jadwal.store') }}';
                this.form = { title: '', date: '', time: '09:00', location: '', host: '', topic: '', note: '' };
                this.open = true;
            },
            openEdit(m) {
                this.mode = 'edit';
                this.action = m.action;
                this.form = m;
                this.open = true;
            },
        }">
        <x-page-header title="Jadwal Kajian" subtitle="Pertemuan & kegiatan majelis" />

        <div class="space-y-6 px-5 py-5">
            @foreach ([['Akan Datang', $upcoming], ['Sudah Lewat', $past]] as [$label, $list])
                <section>
                    <h2 class="eyebrow mb-3">{{ $label }}</h2>
                    @forelse ($list as $m)
                        <div class="card mb-3 overflow-hidden {{ $label === 'Sudah Lewat' ? 'opacity-80' : '' }}">
                            <div class="flex">
                                <div class="flex w-20 shrink-0 flex-col items-center justify-center {{ $label === 'Akan Datang' ? 'bg-pine-500 text-white' : 'bg-pine-50 text-pine-500' }} py-4">
                                    <span class="text-2xl font-bold leading-none">{{ $m->date->day }}</span>
                                    <span class="mt-1 text-xs uppercase tracking-wide">{{ $m->date->translatedFormat('M y') }}</span>
                                </div>
                                <div class="min-w-0 flex-1 p-4">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="min-w-0 font-display font-semibold text-ink">{{ $m->title }}</h3>
                                        @can('manage-meetings')
                                            @php
                                                $editData = ['action' => route('jadwal.update', $m), 'title' => $m->title, 'date' => $m->date->toDateString(), 'time' => $m->time, 'location' => $m->location, 'host' => $m->host, 'topic' => $m->topic, 'note' => $m->note];
                                            @endphp
                                            <div class="flex shrink-0 gap-1.5">
                                                <button type="button" class="flex h-8 w-8 items-center justify-center rounded-lg bg-pine-50 text-pine-500"
                                                        @click='openEdit(@json($editData))'>
                                                    <x-icon name="pencil" size="15" />
                                                </button>
                                                <form method="POST" action="{{ route('jadwal.destroy', $m) }}"
                                                      onsubmit="return confirm('Hapus kegiatan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-100 text-rose-500">
                                                        <x-icon name="trash" size="15" />
                                                    </button>
                                                </form>
                                            </div>
                                        @endcan
                                    </div>
                                    <p class="mt-1 flex items-center gap-1 text-sm text-ink-soft">
                                        <x-icon name="clock" size="14" /> {{ $m->date->translatedFormat('l') }}, {{ $m->time }} WIB
                                    </p>
                                    <p class="mt-0.5 flex items-center gap-1 text-sm text-ink-soft">
                                        <x-icon name="map-pin" size="14" /> {{ $m->location }}
                                    </p>
                                    @if ($m->topic)
                                        <span class="chip mt-2 bg-pine-50 text-pine-600">{{ $m->topic }}</span>
                                    @endif
                                    @if ($m->note)
                                        <p class="mt-2 text-xs text-ink-faint">{{ $m->note }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card p-5 text-center text-sm text-ink-soft">
                            {{ $label === 'Akan Datang' ? 'Belum ada kegiatan terjadwal.' : 'Belum ada riwayat kegiatan.' }}
                        </div>
                    @endforelse
                </section>
            @endforeach
        </div>

        @can('manage-meetings')
            <x-fab label="Tambah" @click="openAdd()" />

            <x-sheet title="Kegiatan">
                <form method="POST" :action="action" class="space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="label">Judul Kegiatan</label>
                        <input class="field" name="title" x-model="form.title" placeholder="Kajian Tafsir…" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label">Tanggal</label>
                            <input class="field" type="date" name="date" x-model="form.date" required>
                        </div>
                        <div>
                            <label class="label">Jam</label>
                            <input class="field" type="time" name="time" x-model="form.time" required>
                        </div>
                    </div>
                    <div>
                        <label class="label">Tempat</label>
                        <input class="field" name="location" x-model="form.location" placeholder="Rumah Ibu…" required>
                    </div>
                    <div>
                        <label class="label">Tuan Rumah</label>
                        <input class="field" name="host" x-model="form.host" placeholder="Nama ibu tuan rumah">
                    </div>
                    <div>
                        <label class="label">Tema / Materi</label>
                        <input class="field" name="topic" x-model="form.topic" placeholder="Tema kajian">
                    </div>
                    <div>
                        <label class="label">Catatan</label>
                        <textarea class="field" name="note" x-model="form.note" rows="2" placeholder="Info tambahan…"></textarea>
                    </div>
                    <button class="btn-primary w-full" x-text="mode === 'edit' ? 'Simpan Perubahan' : 'Tambah Kegiatan'"></button>
                </form>
            </x-sheet>
        @endcan
    </div>
</x-layout>
