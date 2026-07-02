{{-- Bottom sheet; requires parent Alpine scope with boolean `open` --}}
@props(['title'])
<div x-show="open" x-cloak class="fixed inset-0 z-50 mx-auto max-w-md">
    <div class="absolute inset-0 bg-ink/40 backdrop-blur-sm" @click="open = false"
         x-show="open" x-transition.opacity.duration.200ms></div>
    <div x-show="open"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
         class="absolute inset-x-0 bottom-0 max-h-[88dvh] overflow-y-auto rounded-t-3xl bg-surface p-5 shadow-lift"
         style="padding-bottom: calc(1.25rem + var(--safe-bottom))">
        <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-pine-100"></div>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-lg font-semibold text-ink">{{ $title }}</h2>
            <button type="button" @click="open = false"
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-base text-ink-soft">
                <x-icon name="x" size="16" />
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
