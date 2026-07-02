<x-layout title="Menunggu Persetujuan" :nav="false">
    <div class="motif-pine flex min-h-dvh flex-col items-center justify-center px-6 py-10 text-center">
        <div class="card animate-fade-up w-full p-8">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gold-400/15 text-gold-600">
                <x-icon name="clock" size="30" />
            </div>
            <h1 class="mt-4 font-display text-2xl font-semibold text-ink">Menunggu Persetujuan</h1>
            <p class="mt-2 text-sm leading-relaxed text-ink-soft">
                Permintaan Anda untuk bergabung ke
                <span class="font-semibold text-pine-600">{{ auth()->user()->group->name }}</span>
                sudah terkirim. Mohon tunggu koordinator menyetujui keanggotaan Anda.
            </p>
            <p class="mt-3 rounded-xl bg-base p-3 text-xs text-ink-faint">
                Tips: hubungi koordinator majelis Anda agar permintaan segera diproses.
                Setelah disetujui, masuk kembali seperti biasa.
            </p>
            <form method="POST" action="{{ route('logout') }}" class="mt-5">
                @csrf
                <button class="btn-ghost w-full">Keluar</button>
            </form>
        </div>
        <p class="mt-6 text-xs text-pine-100/80">Ruang Ukhuwah · {{ auth()->user()->email }}</p>
    </div>
</x-layout>
