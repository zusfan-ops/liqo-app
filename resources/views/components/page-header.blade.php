@props(['title', 'subtitle' => null, 'back' => null])
<header class="sticky top-0 z-30 border-b border-pine-100/60 bg-base/90 px-5 py-4 backdrop-blur">
    <div class="flex items-center gap-3">
        @if ($back)
            <a href="{{ $back }}" class="flex h-9 w-9 items-center justify-center rounded-xl bg-surface text-ink-soft shadow-soft">
                <x-icon name="chevron-left" />
            </a>
        @endif
        <div class="min-w-0 flex-1">
            <h1 class="truncate font-display text-xl font-semibold text-ink">{{ $title }}</h1>
            @if ($subtitle)
                <p class="truncate text-xs text-ink-faint">{{ $subtitle }}</p>
            @endif
        </div>
        {{ $slot }}
    </div>
</header>
