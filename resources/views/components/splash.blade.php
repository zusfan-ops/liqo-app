@php $hadits = collect(config('hadits'))->random(); @endphp
{{-- Splash screen: tampil saat aplikasi dibuka (sekali per sesi), hadits acak tiap tampil --}}
<div id="ru-splash"
     class="motif-pine fixed inset-0 z-[100] mx-auto flex max-w-md flex-col items-center justify-center px-8 text-center text-white transition-opacity duration-500">
    <div class="animate-fade-up">
        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-[1.75rem] bg-white/10 p-3 shadow-lift backdrop-blur">
            <img src="/icons/icon-192.png" alt="Ruang Ukhuwah" class="h-full w-full rounded-2xl">
        </div>
        <h1 class="mt-4 font-display text-2xl font-bold">Ruang Ukhuwah</h1>
        <p class="mt-0.5 text-xs tracking-wide text-pine-100">Majelis dalam genggaman</p>
    </div>

    <div class="mt-10 animate-fade-up" style="animation-delay: .25s">
        <p class="font-arabic text-xl text-gold-400">﷽</p>
        <blockquote class="mx-auto mt-3 max-w-xs font-display text-[0.95rem] leading-relaxed text-pine-100">
            &ldquo;{{ $hadits['text'] }}&rdquo;
        </blockquote>
        <p class="mt-2 text-xs font-semibold tracking-wide text-gold-400">— {{ $hadits['source'] }}</p>
    </div>

    <div class="absolute bottom-10 flex gap-1.5">
        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold-400"></span>
        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold-400" style="animation-delay: .2s"></span>
        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gold-400" style="animation-delay: .4s"></span>
    </div>
</div>
<script>
    (function () {
        const splash = document.getElementById('ru-splash');
        if (sessionStorage.getItem('ru-splash-shown')) {
            splash.remove(); // navigasi antar halaman: jangan tampil lagi
            return;
        }
        sessionStorage.setItem('ru-splash-shown', '1');
        document.documentElement.style.overflow = 'hidden';
        setTimeout(() => {
            splash.style.opacity = '0';
            document.documentElement.style.overflow = '';
            setTimeout(() => splash.remove(), 500);
        }, 2500);
    })();
</script>
