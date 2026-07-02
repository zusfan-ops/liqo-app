@props(['title' => null, 'nav' => true])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#114536">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" href="/favicon-32.png" sizes="32x32">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base font-sans text-ink antialiased">
    <x-splash />
    <x-install-prompt />
    <div class="relative mx-auto min-h-dvh w-full max-w-md {{ $nav ? 'pb-24' : '' }}">
        @if (session('ok'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2600)" x-show="show" x-cloak
                 x-transition.opacity.duration.300ms
                 class="fixed inset-x-0 top-4 z-[70] mx-auto w-fit max-w-[90%] rounded-full bg-pine-700 px-5 py-2.5 text-sm font-semibold text-white shadow-lift">
                {{ session('ok') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-5 mt-4 rounded-2xl border border-rose-300 bg-rose-100 p-4 text-sm text-rose-600">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </div>

    @if ($nav)
        <x-bottom-nav />
    @endif
</body>
</html>
