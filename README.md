# MIKHMON MOD V6 dan V7 by TESLATECH

Aplikasi web untuk mengelola hotspot dan PPPoE MikroTik dari browser. Modifikasi ini mendukung RouterOS 6 dan RouterOS 7, termasuk perbedaan format tanggal jam router.

Aplikasi asli MIKHMON dibuat oleh Laksamadi Guko dan dilisensikan di bawah GPLv2. Koneksi ke router memakai kelas [RouterOS API](https://github.com/BenMenking/routeros-api).

## Fitur

- Dashboard router dan status koneksi
- Pengguna hotspot: daftar, tambah, generate, aktifkan, dan nonaktifkan
- Profil hotspot, user aktif, host, IP binding, dan cookie
- Voucher dan quick print, plus editor template voucher
- Laporan penjualan dan log pengguna
- PPPoE: secret, profil, dan koneksi aktif
- DHCP lease dan traffic monitor
- Scheduler, reboot, dan shutdown router
- Beberapa router dalam satu instalasi
- Tema: blue, dark, green, light, pink
- Bahasa: Indonesia, English, Spanish, Tagalog, Turkish

## Kebutuhan

- PHP dengan ekstensi `sockets`
- Web server (Apache atau Nginx)
- MikroTik dengan layanan API aktif (port bawaan `8728`)
- `include/config.php` dapat ditulis oleh user web server

## Instalasi

1. Salin isi proyek ini ke document root web server.
2. Pastikan `include/config.php` boleh ditulis oleh PHP.
3. Di MikroTik, aktifkan API lewat **IP → Services → api**.
4. Buka `admin.php` di browser, lalu masuk.
5. Tambah router, isi IP, username, dan password MikroTik, kemudian simpan dan hubungkan.

## Akun dan data router

Tidak ada database. Akun admin dan daftar router disimpan di `include/config.php`.

- Akun login ada di `$data['mikhmon']`. Username dipisah penanda `<|<`, password dipisah `>|>` dan disimpan terenkripsi.
- Setiap router adalah satu array `$data['nama-sesi']`. Password MikroTik juga disimpan terenkripsi.
- Setelah login berhasil, aplikasi hanya menyimpan nama user di session PHP.

Jangan membagikan `include/config.php`. File itu berisi kredensial admin dan router.

## Lisensi

GPLv2. Lihat pemberitahuan hak cipta di berkas sumber. Hak cipta asli © 2018 Laksamadi Guko.
