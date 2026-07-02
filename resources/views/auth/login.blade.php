@php
    $features = [
        ['icon' => 'calendar-days', 'label' => 'Jadwal Kajian', 'desc' => 'Atur pertemuan & tema', 'tone' => 'bg-pine-50 text-pine-500'],
        ['icon' => 'clipboard-check', 'label' => 'Absensi', 'desc' => 'Catat kehadiran otomatis', 'tone' => 'bg-rose-100 text-rose-500'],
        ['icon' => 'wallet', 'label' => 'Kas Majelis', 'desc' => 'Saldo transparan bersama', 'tone' => 'bg-gold-400/15 text-gold-600'],
        ['icon' => 'megaphone', 'label' => 'Pengumuman', 'desc' => 'Info sampai ke semua', 'tone' => 'bg-pine-50 text-pine-500'],
        ['icon' => 'book-open', 'label' => 'Materi Kajian', 'desc' => 'Resume tersimpan rapi', 'tone' => 'bg-rose-100 text-rose-500'],
        ['icon' => 'book-marked', 'label' => 'Tilawah Harian', 'desc' => 'Target, streak & grafik', 'tone' => 'bg-gold-400/15 text-gold-600'],
        ['icon' => 'clock', 'label' => 'Jadwal Sholat', 'desc' => 'Metode Kemenag, 15 kota', 'tone' => 'bg-pine-50 text-pine-500'],
        ['icon' => 'heart-handshake', 'label' => 'Doa Harian', 'desc' => 'Arab, latin & artinya', 'tone' => 'bg-rose-100 text-rose-500'],
    ];
    $stepsKoordinator = [
        ['Daftar sebagai Koordinator', 'Ketuk "Daftar di sini", pilih peran Koordinator, lalu beri nama majelis Anda. Grup langsung jadi.'],
        ['Dapatkan kode undangan', 'Sistem membuat kode grup unik 6 karakter secara otomatis, contoh: ANNISA.'],
        ['Undang ibu-ibu via WhatsApp', 'Di halaman Anggota ada tombol "Undang via WA" — pesan ajakan + kode terkirim sekali ketuk.'],
        ['Kelola majelis bersama', 'Susun jadwal, catat absensi & kas, buat pengumuman. Semua anggota melihat data yang sama.'],
    ];
    $stepsAnggota = [
        ['Minta kode grup', 'Tanyakan kode grup majelis kepada koordinator Anda (6 karakter, mis. ANNISA).'],
        ['Daftar sebagai Anggota', 'Ketuk "Daftar di sini", pilih peran Anggota, lalu masukkan kode tersebut — langsung aktif.'],
        ['Tanpa kode? Tetap bisa', 'Pilih nama grup dari daftar, lalu tunggu persetujuan koordinator sebelum bisa masuk.'],
        ['Ikuti kegiatan majelis', 'Lihat jadwal & pengumuman, cek kas, baca materi, dan catat tilawah harian pribadi Anda.'],
    ];
    $roles = [
        ['Koordinator', 'Mengelola semuanya: anggota, jadwal, kas, hingga pengaturan grup.', 'bg-pine-500 text-white'],
        ['Sekretaris', 'Mengurus jadwal, absensi, pengumuman, dan materi kajian.', 'bg-gold-400/20 text-gold-600'],
        ['Bendahara', 'Mencatat pemasukan & pengeluaran kas majelis.', 'bg-rose-100 text-rose-500'],
        ['Anggota', 'Melihat semua info majelis & mencatat tilawah pribadi.', 'bg-base text-ink-soft'],
    ];
@endphp
<x-layout title="Masuk" :nav="false">
    {{-- ══════════ HERO ══════════ --}}
    <header class="motif-pine relative overflow-hidden rounded-b-[2.5rem] px-6 pb-20 pt-12 text-center text-white shadow-lift">
        <p class="font-arabic text-3xl text-gold-400">السَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللهِ</p>
        <h1 class="mt-3 font-display text-4xl font-bold tracking-tight">Ruang Ukhuwah</h1>
        <p class="mx-auto mt-2 max-w-xs text-sm leading-relaxed text-pine-100">
            Satu aplikasi untuk seluruh kegiatan liqo &amp; pengajian ibu-ibu —
            jadwal, absensi, kas, hingga tilawah, tersinkron untuk semua anggota.
        </p>
        <div class="mt-5 flex flex-wrap items-center justify-center gap-2 text-[0.7rem] font-semibold">
            <span class="rounded-full bg-white/10 px-3 py-1.5">📱 Bisa dipasang di HP</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5">👥 Multi-grup majelis</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5">🔒 Data per grup terjaga</span>
        </div>
    </header>

    {{-- ══════════ LOGIN CARD (menimpa hero) ══════════ --}}
    <div class="relative z-10 -mt-12 px-5">
        <div class="card animate-fade-up p-6 shadow-card">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-xl font-semibold text-ink">Masuk</h2>
                <span class="chip bg-pine-50 text-pine-600">Sudah punya akun</span>
            </div>

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label" for="email">Email</label>
                    <input class="field" type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@contoh.com" required autofocus>
                </div>
                <div>
                    <label class="label" for="password">Kata Sandi</label>
                    <input class="field" type="password" id="password" name="password" required>
                </div>
                <label class="flex items-center gap-2 text-sm text-ink-soft">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-pine-200 accent-pine-500">
                    Ingat saya di perangkat ini
                </label>
                <button type="submit" class="btn-primary w-full">Masuk</button>
            </form>

            <div class="mt-4 rounded-xl bg-base p-3 text-center text-sm text-ink-soft">
                Baru di sini?
                <a href="{{ route('register') }}" class="font-semibold text-pine-500">Daftar di sini</a>
                — buat grup baru atau gabung majelis Anda.
            </div>
        </div>
    </div>

    <div class="space-y-8 px-5 pb-10 pt-8">
        {{-- ══════════ APA ITU ══════════ --}}
        <section>
            <h2 class="eyebrow mb-1">Apa itu Ruang Ukhuwah?</h2>
            <p class="text-sm leading-relaxed text-ink-soft">
                Biasanya info majelis tercecer di banyak chat: jadwal berubah, catatan kas di buku,
                absensi di kertas. <span class="font-semibold text-ink">Ruang Ukhuwah</span> merapikan
                semuanya di satu tempat — setiap anggota membuka aplikasi dan melihat data yang sama,
                langsung dari HP masing-masing.
            </p>
            <div class="mt-4 grid grid-cols-2 gap-3">
                @foreach ($features as $f)
                    <div class="card flex items-start gap-3 p-3.5">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $f['tone'] }}">
                            <x-icon :name="$f['icon']" size="19" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold leading-tight text-ink">{{ $f['label'] }}</p>
                            <p class="mt-0.5 text-[0.7rem] leading-snug text-ink-faint">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ══════════ TUTORIAL ══════════ --}}
        <section x-data="{ tab: 'koordinator' }">
            <h2 class="eyebrow mb-1">Cara Menggunakan</h2>
            <p class="mb-3 text-sm text-ink-soft">Pilih sesuai peran Anda:</p>

            <div class="grid grid-cols-2 gap-2 rounded-xl bg-white p-1.5 shadow-soft">
                <button type="button" @click="tab = 'koordinator'"
                        class="rounded-lg py-2.5 text-sm font-semibold transition"
                        :class="tab === 'koordinator' ? 'bg-pine-500 text-white shadow-soft' : 'text-ink-soft'">
                    👑 Saya Koordinator
                </button>
                <button type="button" @click="tab = 'anggota'"
                        class="rounded-lg py-2.5 text-sm font-semibold transition"
                        :class="tab === 'anggota' ? 'bg-pine-500 text-white shadow-soft' : 'text-ink-soft'">
                    🌸 Saya Anggota
                </button>
            </div>

            @foreach (['koordinator' => $stepsKoordinator, 'anggota' => $stepsAnggota] as $tabName => $steps)
                <div x-show="tab === '{{ $tabName }}'" x-cloak x-transition.opacity.duration.200ms class="mt-4 space-y-0">
                    @foreach ($steps as $i => [$judul, $desc])
                        <div class="relative flex gap-4 pb-5 {{ $loop->last ? '' : '' }}">
                            @unless ($loop->last)
                                <span class="absolute left-[1.05rem] top-10 h-[calc(100%-2rem)] w-0.5 rounded bg-pine-100"></span>
                            @endunless
                            <span class="z-10 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-pine-500 font-display text-sm font-bold text-white shadow-soft">
                                {{ $i + 1 }}
                            </span>
                            <div class="card flex-1 p-4">
                                <p class="font-semibold text-ink">{{ $judul }}</p>
                                <p class="mt-1 text-sm leading-relaxed text-ink-soft">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <a href="{{ route('register') }}" class="btn-primary w-full"
               x-text="tab === 'koordinator' ? '✨ Buat Grup Majelis Sekarang' : '🤝 Gabung Majelis Sekarang'"></a>
        </section>

        {{-- ══════════ PERAN ══════════ --}}
        <section>
            <h2 class="eyebrow mb-3">Empat Peran dalam Grup</h2>
            <div class="card divide-y divide-pine-100/60 overflow-hidden">
                @foreach ($roles as [$nama, $desc, $tone])
                    <div class="flex items-center gap-3 px-4 py-3.5">
                        <span class="chip shrink-0 {{ $tone }}">{{ $nama }}</span>
                        <p class="text-sm leading-snug text-ink-soft">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
            <p class="mt-2 text-xs text-ink-faint">
                Koordinator dapat mengubah peran anggota kapan saja dari halaman Anggota.
            </p>
        </section>

        {{-- ══════════ TIPS PWA ══════════ --}}
        <section x-data="{ open: false }">
            <button type="button" @click="open = !open"
                    class="card flex w-full items-center justify-between p-4 text-left">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold-400/15 text-gold-600">
                        <x-icon name="sparkles" size="19" />
                    </span>
                    <p class="font-semibold text-ink">Pasang seperti aplikasi di HP</p>
                </div>
                <span class="text-ink-faint transition" :class="open ? 'rotate-180' : ''">
                    <x-icon name="chevron-down" size="18" />
                </span>
            </button>
            <div x-show="open" x-cloak x-transition class="card mt-2 space-y-2 p-4 text-sm leading-relaxed text-ink-soft">
                <p><span class="font-semibold text-ink">Android (Chrome):</span> buka menu ⋮ di pojok kanan atas → ketuk <em>"Tambahkan ke layar utama"</em>.</p>
                <p><span class="font-semibold text-ink">iPhone (Safari):</span> ketuk tombol Bagikan <span class="font-semibold">⎋</span> → <em>"Tambah ke Layar Utama"</em>.</p>
                <p class="rounded-xl bg-base p-3 text-xs text-ink-faint">Setelah terpasang, Ruang Ukhuwah terbuka layar penuh seperti aplikasi biasa — tanpa perlu unduh dari Play Store.</p>
            </div>
        </section>

        <p class="text-center text-xs text-ink-faint">
            Ruang Ukhuwah · Majelis dalam genggaman<br>
            <span class="font-arabic text-sm text-pine-400">بَارَكَ اللهُ فِيكُنَّ</span>
        </p>
    </div>
</x-layout>
