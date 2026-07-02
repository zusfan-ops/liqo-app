# Ruang Ukhuwah (Laravel + MySQL)

Aplikasi web mobile pendamping kegiatan liqo / pengajian ibu-ibu — versi **Laravel 13 + MySQL**
dengan **login multi-user**, hasil migrasi dari versi Next.js (folder `../liqo`).

## Teknologi

- Laravel 13 (PHP 8.3) + MySQL 8 (database `liqo`)
- Blade + Tailwind CSS v4 (Vite) + Alpine.js
- PWA: manifest + service worker (bisa "Add to Home Screen")
- Jadwal sholat via API Aladhan (metode Kemenag), di-cache 6 jam di server

## Registrasi & grup (multi-majelis)

Aplikasi mendukung **banyak grup majelis** dalam satu database. Alur pendaftaran (`/daftar`):

- **Koordinator** — mengisi nama majelis baru → grup dibuat otomatis beserta
  **kode undangan** 6 karakter (mis. `ANNISA`). Kode tampil di halaman Anggota &
  Pengaturan, lengkap dengan tombol **Undang via WhatsApp**.
- **Anggota** — bergabung ke grup yang sudah ada dengan **memasukkan kode grup**
  dari koordinator, atau **memilih nama grup dari daftar**.

Semua data (jadwal, kas, absensi, pengumuman, materi) terisolasi per grup —
antar majelis tidak bisa saling melihat data. Koordinator juga tetap bisa
menambahkan akun anggota secara manual dari halaman Anggota.

> Catatan: opsi "pilih dari daftar" memungkinkan siapa pun bergabung tanpa kode.
> Jika privasi grup penting, minta anggota selalu memakai kode (opsi daftar bisa
> dihilangkan dengan mudah di `resources/views/auth/register.blade.php`).

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

Kode grup contoh: **`ANNISA`**. Untuk mulai dari data kosong: `php artisan migrate:fresh`
(tanpa `--seed`), lalu daftar sebagai Koordinator lewat halaman `/daftar`.

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
