<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\FinanceEntry;
use App\Models\Meeting;
use App\Models\Note;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'group_name' => "Majelis Ta'lim An-Nisa",
            'coordinator' => 'Ustadzah Halimah',
            'city' => 'Bandung',
            'country' => 'Indonesia',
            'tilawah_target' => 4,
        ]);

        // Semua akun di bawah memakai kata sandi: ukhuwah123
        $password = 'ukhuwah123';
        $members = [
            ['Ustadzah Halimah', 'halimah@ukhuwah.id', 'Koordinator', '081234500001', 'Jl. Cempaka No. 12', -400],
            ['Ibu Siti Aminah', 'siti@ukhuwah.id', 'Sekretaris', '081234500002', 'Jl. Melati No. 4', -380],
            ['Ibu Nur Hasanah', 'nur@ukhuwah.id', 'Bendahara', '081234500003', 'Jl. Mawar No. 21', -370],
            ['Ibu Fatimah', 'fatimah@ukhuwah.id', 'Anggota', '081234500004', 'Jl. Anggrek No. 8', -300],
            ['Ibu Khadijah', 'khadijah@ukhuwah.id', 'Anggota', '081234500005', 'Jl. Kenanga No. 15', -250],
            ['Ibu Maryam', 'maryam@ukhuwah.id', 'Anggota', '081234500006', 'Jl. Dahlia No. 3', -200],
            ['Ibu Aisyah', 'aisyah@ukhuwah.id', 'Anggota', '081234500007', 'Jl. Flamboyan No. 9', -120],
            ['Ibu Zainab', 'zainab@ukhuwah.id', 'Anggota', '081234500008', 'Jl. Teratai No. 6', -60],
        ];
        $users = collect($members)->map(fn ($m) => User::create([
            'name' => $m[0],
            'email' => $m[1],
            'password' => $password,
            'role' => $m[2],
            'phone' => $m[3],
            'address' => $m[4],
            'join_date' => today()->addDays($m[5]),
        ]));

        $meetings = collect([
            ['Kajian Tafsir Surah Ar-Rahman', 3, 'Rumah Ibu Fatimah', 'Ibu Fatimah', 'Tafsir Surah Ar-Rahman ayat 1–13', "Membawa Al-Qur'an dan buku catatan. Infaq konsumsi sukarela."],
            ['Liqo Pekanan & Setoran Hafalan', 10, 'Rumah Ibu Khadijah', 'Ibu Khadijah', "Muraja'ah Juz 30 + kajian adab menuntut ilmu", 'Setoran hafalan Surah An-Naba.'],
            ['Kajian Fikih Wanita', -4, 'Rumah Ibu Nur Hasanah', 'Ibu Nur Hasanah', 'Thaharah: bersuci & hal-hal yang membatalkan wudhu', null],
            ['Liqo Pekanan', -11, 'Rumah Ibu Siti Aminah', 'Ibu Siti Aminah', 'Sirah Nabawiyah: keteladanan Ummahatul Mukminin', null],
        ])->map(fn ($m) => Meeting::create([
            'title' => $m[0],
            'date' => today()->addDays($m[1]),
            'time' => '09:00',
            'location' => $m[2],
            'host' => $m[3],
            'topic' => $m[4],
            'note' => $m[5],
        ]));

        // Absensi untuk 2 pertemuan yang sudah lewat
        foreach ([
            2 => ['hadir', 'hadir', 'hadir', 'izin', 'hadir', 'hadir', 'sakit', 'hadir'],
            3 => ['hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'izin', 'hadir', 'alpa'],
        ] as $meetingIdx => $statuses) {
            foreach ($statuses as $userIdx => $status) {
                Attendance::create([
                    'meeting_id' => $meetings[$meetingIdx]->id,
                    'user_id' => $users[$userIdx]->id,
                    'status' => $status,
                ]);
            }
        }

        $bendahara = $users[2];
        foreach ([
            [-30, 'masuk', 'Iuran Bulanan', 400000, 'Iuran 8 anggota @Rp50.000'],
            [-28, 'keluar', 'Konsumsi', 150000, 'Snack kajian'],
            [-20, 'masuk', 'Infaq', 250000, "Infaq sukarela jama'ah"],
            [-14, 'keluar', 'Santunan', 300000, 'Santunan anak yatim'],
            [-4, 'masuk', 'Iuran Bulanan', 400000, 'Iuran bulan ini'],
            [-2, 'keluar', 'Perlengkapan', 85000, 'Spidol & kertas'],
        ] as $f) {
            FinanceEntry::create([
                'date' => today()->addDays($f[0]),
                'type' => $f[1],
                'category' => $f[2],
                'amount' => $f[3],
                'note' => $f[4],
                'user_id' => $bendahara->id,
            ]);
        }

        Announcement::create([
            'title' => 'Perubahan Lokasi Kajian Pekan Ini',
            'body' => "Assalamu'alaikum ibu-ibu. Kajian pekan ini insyaAllah diadakan di rumah Ibu Fatimah, Jl. Anggrek No. 8. Mohon hadir tepat waktu pukul 09.00. Jazakunnallahu khairan.",
            'pinned' => true,
            'user_id' => $users[0]->id,
        ]);
        Announcement::create([
            'title' => 'Program Tahfizh Juz 30',
            'body' => 'Mulai bulan ini kita membuka program setoran hafalan Juz 30 setiap pekan. Yang berkenan ikut mohon mendaftar ke Ibu Siti Aminah.',
            'pinned' => false,
            'user_id' => $users[1]->id,
        ]);

        Note::create([
            'date' => today()->subDays(4),
            'title' => 'Ringkasan: Thaharah (Bersuci)',
            'speaker' => 'Ustadzah Halimah',
            'content' => "Thaharah adalah syarat sah ibadah. Poin penting:\n\n1. Air suci mensucikan: air hujan, mata air, sumur, sungai, laut.\n2. Rukun wudhu: niat, membasuh wajah, tangan sampai siku, mengusap kepala, membasuh kaki sampai mata kaki, tertib.\n3. Pembatal wudhu: keluar sesuatu dari dua jalan, hilang akal, menyentuh kemaluan tanpa penghalang.\n\nHikmah: kebersihan sebagian dari iman, dan menjaga kesucian mendekatkan diri kepada Allah.",
        ]);
        Note::create([
            'date' => today()->subDays(11),
            'title' => 'Keteladanan Khadijah binti Khuwailid',
            'speaker' => 'Ustadzah Halimah',
            'content' => 'Khadijah r.a. adalah istri pertama Rasulullah ﷺ, wanita mulia yang menjadi penopang dakwah di masa awal. Beliau mendukung dengan harta, waktu, dan keteguhan iman. Pelajaran: seorang muslimah dapat menjadi pilar keluarga dan dakwah dengan ketulusan dan kesabaran.',
        ]);

        foreach ([
            [0, 4, 'Al-Baqarah', "Ba'da subuh"],
            [-1, 5, 'Al-Baqarah', null],
            [-2, 3, 'Ali Imran', 'Sedikit sibuk'],
            [-3, 4, 'Ali Imran', null],
            [-5, 6, 'An-Nisa', 'Setelah kajian'],
        ] as $t) {
            $users[0]->tilawahEntries()->create([
                'date' => today()->addDays($t[0]),
                'pages' => $t[1],
                'surah' => $t[2],
                'note' => $t[3],
            ]);
        }
    }
}
