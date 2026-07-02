<x-layout title="Doa Harian">
    <x-page-header title="Doa Harian" subtitle="{{ count($doa) }} doa pilihan" />

    <div class="space-y-3 px-5 py-5">
        @foreach ($doa as $d)
            <div class="card p-5">
                <h3 class="font-display font-semibold text-pine-600">{{ $d['title'] }}</h3>
                <p class="mt-3 text-right font-arabic text-2xl leading-loose text-ink" dir="rtl">{{ $d['arabic'] }}</p>
                <p class="mt-3 text-sm italic text-ink-soft">{{ $d['latin'] }}</p>
                <p class="mt-2 border-t border-pine-100/60 pt-2 text-sm leading-relaxed text-ink-soft">
                    &ldquo;{{ $d['arti'] }}&rdquo;
                </p>
            </div>
        @endforeach
    </div>
</x-layout>
