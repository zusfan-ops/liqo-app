<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimes
{
    public const CITIES = [
        'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang',
        'Makassar', 'Yogyakarta', 'Bogor', 'Bekasi', 'Depok',
        'Tangerang', 'Palembang', 'Malang', 'Padang', 'Denpasar',
    ];

    public const LABELS = [
        'Fajr' => 'Subuh',
        'Sunrise' => 'Terbit',
        'Dhuhr' => 'Dzuhur',
        'Asr' => 'Ashar',
        'Maghrib' => 'Maghrib',
        'Isha' => 'Isya',
    ];

    /**
     * @return array{timings: array<string,string>, hijri: ?string}|null
     */
    public static function today(string $city, string $country = 'Indonesia'): ?array
    {
        $key = 'prayer:'.mb_strtolower($city).':'.now()->toDateString();

        return Cache::remember($key, now()->addHours(6), function () use ($city, $country) {
            try {
                // method=20 = Kemenag RI
                $response = Http::timeout(8)->get('https://api.aladhan.com/v1/timingsByCity', [
                    'city' => $city,
                    'country' => $country,
                    'method' => 20,
                ]);

                $data = $response->json('data');
                if (! $response->ok() || ! isset($data['timings'])) {
                    return null;
                }

                $timings = collect($data['timings'])
                    ->only(array_keys(self::LABELS))
                    ->map(fn ($t) => substr($t, 0, 5))
                    ->all();

                $h = $data['date']['hijri'] ?? null;
                $hijri = $h ? "{$h['day']} {$h['month']['en']} {$h['year']} H" : null;

                return ['timings' => $timings, 'hijri' => $hijri];
            } catch (\Throwable) {
                return null;
            }
        });
    }

    /**
     * @param  array<string,string>  $timings
     * @return array{label: string, time: string}
     */
    public static function next(array $timings): array
    {
        $nowMinutes = now()->hour * 60 + now()->minute;

        foreach (self::LABELS as $key => $label) {
            if ($key === 'Sunrise' || ! isset($timings[$key])) {
                continue;
            }
            [$h, $m] = array_map('intval', explode(':', $timings[$key]));
            if ($h * 60 + $m >= $nowMinutes) {
                return ['label' => $label, 'time' => $timings[$key]];
            }
        }

        return ['label' => 'Subuh', 'time' => $timings['Fajr'] ?? '—'];
    }
}
