@php
    $roleChip = [
        'Koordinator' => 'bg-pine-500 text-white',
        'Sekretaris' => 'bg-gold-400/20 text-gold-600',
        'Bendahara' => 'bg-rose-100 text-rose-500',
        'Anggota' => 'bg-base text-ink-soft',
    ];
@endphp
<x-layout title="Anggota">
    <div x-data="{
            open: false,
            mode: 'add',
            action: '{{ route('anggota.store') }}',
            form: { name: '', email: '', role: 'Anggota', phone: '', address: '', join_date: '' },
            openAdd() {
                this.mode = 'add';
                this.action = '{{ route('anggota.store') }}';
                this.form = { name: '', email: '', role: 'Anggota', phone: '', address: '', join_date: '{{ today()->toDateString() }}' };
                this.open = true;
            },
            openEdit(m) {
                this.mode = 'edit';
                this.action = m.action;
                this.form = m;
                this.open = true;
            },
        }">
        <x-page-header title="Anggota" subtitle="{{ $members->count() }} anggota majelis" />

        <div class="space-y-3 px-5 py-5">
            @can('manage-members')
                @php
                    $inviteText = "Assalamu'alaikum! Yuk gabung grup {$group->name} di aplikasi Ruang Ukhuwah. Daftar di ".route('register').' sebagai Anggota, lalu masukkan kode grup: '.$group->code;
                @endphp
                <div class="motif-pine rounded-3xl p-5 text-white shadow-lift">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="eyebrow text-pine-100">Kode Undangan Grup</h2>
                            <p class="mt-1 font-display text-3xl font-bold tracking-[0.25em]">{{ $group->code }}</p>
                            <p class="mt-1 text-xs text-pine-100">Bagikan kode ini — calon anggota memasukkannya saat mendaftar.</p>
                        </div>
                        <a href="https://wa.me/?text={{ urlencode($inviteText) }}" target="_blank" rel="noopener"
                           class="flex shrink-0 flex-col items-center gap-1 rounded-2xl bg-white/10 px-4 py-3 text-xs font-semibold">
                            <x-icon name="message-circle" size="22" class="text-gold-400" />
                            Undang via WA
                        </a>
                    </div>
                </div>

                @if ($pending->isNotEmpty())
                    <section class="card border-gold-400/50 bg-gold-400/[0.06] p-4">
                        <h2 class="eyebrow text-gold-600">Menunggu Persetujuan ({{ $pending->count() }})</h2>
                        <div class="mt-3 space-y-3">
                            @foreach ($pending as $p)
                                <div class="flex items-center gap-3 rounded-xl bg-surface p-3 shadow-soft">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gold-400/15 font-bold text-gold-600">
                                        {{ $p->initials() }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-semibold text-ink">{{ $p->name }}</p>
                                        <p class="truncate text-xs text-ink-faint">{{ $p->email }}@if ($p->phone) · {{ $p->phone }}@endif</p>
                                    </div>
                                    <form method="POST" action="{{ route('anggota.approve', $p) }}">
                                        @csrf
                                        <button class="flex h-9 w-9 items-center justify-center rounded-xl bg-pine-500 text-white" title="Setujui">
                                            <x-icon name="check" size="16" />
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('anggota.reject', $p) }}"
                                          onsubmit="return confirm('Tolak permintaan {{ $p->name }}? Akunnya akan dihapus.')">
                                        @csrf @method('DELETE')
                                        <button class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-100 text-rose-500" title="Tolak">
                                            <x-icon name="x" size="16" />
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endcan
            @foreach ($members as $u)
                <div class="card p-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-pine-50 font-display text-lg font-bold text-pine-500">
                            {{ $u->initials() }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-ink">{{ $u->name }}</p>
                            <span class="chip mt-1 {{ $roleChip[$u->role] ?? $roleChip['Anggota'] }}">{{ $u->role }}</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            @if ($u->waLink())
                                <a href="{{ $u->waLink() }}" target="_blank" rel="noopener"
                                   class="flex h-9 w-9 items-center justify-center rounded-xl bg-pine-500 text-white"
                                   title="Chat WhatsApp">
                                    <x-icon name="message-circle" size="17" />
                                </a>
                            @endif
                            @can('manage-members')
                                @php
                                    $editData = ['action' => route('anggota.update', $u), 'name' => $u->name, 'email' => $u->email, 'role' => $u->role, 'phone' => $u->phone, 'address' => $u->address, 'join_date' => $u->join_date?->toDateString()];
                                @endphp
                                <button type="button" class="flex h-9 w-9 items-center justify-center rounded-xl bg-pine-50 text-pine-500"
                                        @click='openEdit(@json($editData))'>
                                    <x-icon name="pencil" size="16" />
                                </button>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('anggota.destroy', $u) }}"
                                          onsubmit="return confirm('Hapus anggota {{ $u->name }}? Akun login-nya juga akan dihapus.')">
                                        @csrf @method('DELETE')
                                        <button class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-100 text-rose-500">
                                            <x-icon name="trash" size="16" />
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 border-t border-pine-100/60 pt-3 text-sm text-ink-soft">
                        @if ($u->phone)
                            <p class="flex items-center gap-2"><x-icon name="phone" size="14" /> {{ $u->phone }}</p>
                        @endif
                        @if ($u->address)
                            <p class="flex items-center gap-2"><x-icon name="map-pin" size="14" /> {{ $u->address }}</p>
                        @endif
                        @if ($u->join_date)
                            <p class="text-xs text-ink-faint">Bergabung {{ $u->join_date->translatedFormat('j F Y') }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @can('manage-members')
            <x-fab label="Tambah" @click="openAdd()" />

            <x-sheet title="Anggota">
                <form method="POST" :action="action" class="space-y-4">
                    @csrf
                    <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="label">Nama Lengkap</label>
                        <input class="field" name="name" x-model="form.name" required>
                    </div>
                    <div>
                        <label class="label">Email (untuk login)</label>
                        <input class="field" type="email" name="email" x-model="form.email" required>
                    </div>
                    <div>
                        <label class="label">Peran</label>
                        <div class="relative">
                            <select class="field appearance-none pr-10" name="role" x-model="form.role">
                                @foreach (\App\Models\User::ROLES as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-ink-faint">
                                <x-icon name="chevron-down" size="18" />
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="label">No. HP / WhatsApp</label>
                        <input class="field" name="phone" x-model="form.phone" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="label">Alamat</label>
                        <input class="field" name="address" x-model="form.address">
                    </div>
                    <div>
                        <label class="label">Tanggal Bergabung</label>
                        <input class="field" type="date" name="join_date" x-model="form.join_date">
                    </div>
                    <div>
                        <label class="label" x-text="mode === 'edit' ? 'Kata Sandi Baru (kosongkan jika tetap)' : 'Kata Sandi'"></label>
                        <input class="field" type="password" name="password" :required="mode === 'add'" minlength="6"
                               placeholder="Minimal 6 karakter">
                    </div>
                    <button class="btn-primary w-full" x-text="mode === 'edit' ? 'Simpan Perubahan' : 'Tambah Anggota'"></button>
                </form>
            </x-sheet>
        @endcan
    </div>
</x-layout>
