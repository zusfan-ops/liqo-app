# Ruang Ukhuwah (Laravel + MySQL)

Aplikasi web mobile pendamping kegiatan liqo / pengajian ibu-ibu — versi **Laravel 13 + MySQL**
dengan **login multi-user**, hasil migrasi dari versi Next.js (folder `../liqo`).

## Teknologi

- Laravel 13 (PHP 8.3) + MySQL 8 (database `liqo`)
- Blade + Tailwind CSS v4 (Vite) + Alpine.js
- PWA: manifest + service worker (bisa "Add to Home Screen")
- Jadwal sholat via API Aladhan (metode Kemenag), di-cache 6 jam di server

## Fitur & hak akses

| Fitur | Koordinator | Sekretaris | Bendahara | Anggota |
|---|---|---|---|---|
| Beranda, lihat semua data | ✔ | ✔ | ✔ | ✔ |
| Kelola jadwal, absensi, pengumuman, materi | ✔ | ✔ | — | — |
| Kelola kas (pemasukan/pengeluaran) | ✔ | — | ✔ | — |
| Kelola anggota (buat/ubah/hapus akun) | ✔ | — | — | — |
| Pengaturan majelis & kota sholat | ✔ | — | — | — |
| Tilawah pribadi & ganti sandi sendiri | ✔ | ✔ | ✔ | ✔ |

Setiap anggota adalah user (tabel `users` menyimpan peran, HP, alamat). Tilawah bersifat
pribadi per akun; data lain (jadwal, kas, absensi, pengumuman, materi) terpusat di MySQL
sehingga semua anggota melihat data yang sama.

## Menjalankan

```bash
composer install
npm install && npm run build   # atau `npm run dev` saat pengembangan
php artisan migrate --seed     # butuh database MySQL `liqo`
php artisan serve              # http://localhost:8000
```

Konfigurasi database ada di `.env` (default Laragon: user `root` tanpa password).

## Akun contoh (hasil seeder)

Kata sandi semua akun: **`ukhuwah123`** — segera ganti lewat menu Pengaturan.

| Email | Peran |
|---|---|
| halimah@ukhuwah.id | Koordinator |
| siti@ukhuwah.id | Sekretaris |
| nur@ukhuwah.id | Bendahara |
| fatimah@ / khadijah@ / maryam@ / aisyah@ / zainab@ukhuwah.id | Anggota |

Untuk mulai dari data kosong: `php artisan migrate:fresh`, lalu buat akun Koordinator lewat
tinker (`php artisan tinker`) →
`App\Models\User::create(['name'=>'Nama','email'=>'email@anda.id','password'=>'sandi','role'=>'Koordinator'])`.

## Struktur singkat

```
app/Http/Controllers/   satu controller per fitur
app/Models/             User, Meeting, Attendance, FinanceEntry, Announcement, Note, TilawahEntry, Setting
app/Services/           PrayerTimes (API Aladhan + cache)
config/doa.php          data statis doa harian
database/migrations/    skema tabel
database/seeders/       data contoh + akun
resources/views/        halaman Blade + komponen (layout, bottom-nav, sheet, icon, dll.)
public/                 manifest.webmanifest, sw.js, ikon PWA
```
