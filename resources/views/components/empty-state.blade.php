@props(['icon' => 'sparkles', 'title', 'desc'])
<div class="flex flex-col items-center justify-center px-8 py-16 text-center">
    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-pine-50 text-pine-400">
        <x-icon :name="$icon" size="28" />
    </div>
    <h3 class="font-display text-lg font-semibold text-ink">{{ $title }}</h3>
    <p class="mt-1 max-w-xs text-sm text-ink-soft">{{ $desc }}</p>
</div>
