<x-layout title="Masuk" :nav="false">
    <div class="motif-pine flex min-h-dvh flex-col justify-center px-6 py-10">
        <div class="mb-8 text-center text-white">
            <p class="font-arabic text-2xl text-gold-400">السَّلَامُ عَلَيْكُمْ</p>
            <h1 class="mt-2 font-display text-3xl font-semibold">Ruang Ukhuwah</h1>
            <p class="mt-1 text-sm text-pine-100">Pendamping majelis ibu-ibu</p>
        </div>

        <div class="card animate-fade-up p-6">
            <h2 class="font-display text-xl font-semibold text-ink">Masuk</h2>
            <p class="mt-1 text-sm text-ink-soft">Masuk dengan akun majelis Anda.</p>

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-5 space-y-4">
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

            <p class="mt-4 text-center text-sm text-ink-soft">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-pine-500">Daftar di sini</a>
            </p>
        </div>

        <p class="mt-6 text-center text-xs text-pine-100/80">Ruang Ukhuwah · Majelis dalam genggaman</p>
    </div>
</x-layout>
