{{-- Floating action button; opens Alpine sheet in parent scope --}}
@props(['label' => 'Tambah'])
<button type="button" {{ $attributes }}
        class="fixed bottom-24 left-1/2 z-30 flex -translate-x-1/2 items-center gap-2 rounded-full bg-pine-500 px-6 py-3.5 font-semibold text-white shadow-lift transition active:scale-95 hover:bg-pine-600"
        style="margin-bottom: var(--safe-bottom)">
    <x-icon name="plus" />
    {{ $label }}
</button>
