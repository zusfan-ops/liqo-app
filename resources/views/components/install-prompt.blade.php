{{-- Popup ajakan pasang PWA: Chrome/Android pakai prompt asli, iOS pakai panduan manual --}}
<div id="ru-install" class="fixed inset-x-0 bottom-0 z-[90] mx-auto hidden max-w-md px-4 pb-4"
     style="padding-bottom: calc(1rem + var(--safe-bottom))">
    <div class="card animate-fade-up border-pine-200 p-4 shadow-lift">
        <div class="flex items-start gap-3">
            <img src="/icons/icon-192.png" alt="" class="h-12 w-12 shrink-0 rounded-xl shadow-soft">
            <div class="min-w-0 flex-1">
                <p class="font-display font-semibold text-ink">Pasang Ruang Ukhuwah</p>
                <p id="ru-install-desc" class="mt-0.5 text-xs leading-snug text-ink-soft">
                    Pasang di layar utama agar terbuka layar penuh seperti aplikasi — cepat &amp; praktis.
                </p>
                <div id="ru-install-ios" class="mt-2 hidden rounded-xl bg-base p-2.5 text-xs leading-relaxed text-ink-soft">
                    Di Safari: ketuk tombol <span class="font-semibold text-ink">Bagikan</span>
                    <svg class="inline h-3.5 w-3.5 -translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m8 7 4-4 4 4"/><path d="M4 11v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/></svg>
                    lalu pilih <span class="font-semibold text-ink">&ldquo;Tambah ke Layar Utama&rdquo;</span>.
                </div>
            </div>
            <button type="button" id="ru-install-close"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-base text-ink-faint">
                <x-icon name="x" size="15" />
            </button>
        </div>
        <div id="ru-install-actions" class="mt-3 grid grid-cols-2 gap-2">
            <button type="button" id="ru-install-later" class="btn-ghost py-2.5 text-sm">Nanti Saja</button>
            <button type="button" id="ru-install-go" class="btn-primary py-2.5 text-sm">Pasang Sekarang</button>
        </div>
    </div>
</div>
<script>
    (function () {
        const box = document.getElementById('ru-install');
        const KEY = 'ru-install-dismissed';
        const WEEK = 7 * 24 * 60 * 60 * 1000;

        const standalone = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;
        const dismissedAt = Number(localStorage.getItem(KEY) || 0);
        if (standalone || Date.now() - dismissedAt < WEEK) return;

        const dismiss = () => {
            localStorage.setItem(KEY, String(Date.now()));
            box.classList.add('hidden');
        };
        document.getElementById('ru-install-close').addEventListener('click', dismiss);
        document.getElementById('ru-install-later').addEventListener('click', dismiss);

        // Android / Chrome / Edge: prompt instal asli
        let deferred = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferred = e;
            setTimeout(() => box.classList.remove('hidden'), 3200); // tunggu splash selesai
        });
        document.getElementById('ru-install-go').addEventListener('click', async () => {
            if (!deferred) return dismiss();
            deferred.prompt();
            await deferred.userChoice;
            deferred = null;
            box.classList.add('hidden');
            localStorage.setItem(KEY, String(Date.now()));
        });
        window.addEventListener('appinstalled', () => box.classList.add('hidden'));

        // iOS Safari: tidak ada beforeinstallprompt — tampilkan panduan manual
        const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
        if (isIos) {
            document.getElementById('ru-install-ios').classList.remove('hidden');
            document.getElementById('ru-install-actions').classList.add('hidden');
            document.getElementById('ru-install-desc').classList.add('hidden');
            setTimeout(() => box.classList.remove('hidden'), 3200);
        }
    })();
</script>
