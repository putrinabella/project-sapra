# PERANCANGAN DAN PEMBUATAN SISTEM MANAJEMEN SARANA DAN PRASARANA, IT, DAN LAB SMK TELKOM BANJARBARU

## Deskripsi Sistem 

Web Sistem Manajemen Sarana dan Prasarana, IT, dan Lab SMK Telkom Banjarbaru merupakan sebuah platform yang dirancang khusus untuk menyediakan solusi terpadu dalam pengelolaan fasilitas sekolah, memberikan fokus pada aset fisik, layanan, peminjaman, dan pengelolaan keluhan dengan feedback. Dengan antarmuka yang intuitif, kami bertujuan memberikan kemudahan bagi pengguna dalam melacak inventaris, menjadwalkan pemeliharaan, dan memonitor kondisi operasional secara efisien.

## Aktifkan file environment

Aktifkan file `env` dengan cara menambahkan tanda titik `.` pada bagian depan nama file, sehingga menjadi `.env`

Pada line 17 dan 18 terdapat dua pilihan mode, yaitu **production** dan **development**. Hapus tanda `#` pada mode yang dinginkan untuk menyalakan mode tersebut.

Hapus tanda `#` pada line 17 untuk masuk ke **mode production**

Hapus tanda `#` pada line 18 untuk masuk ke **mode development**

_Catatan_

- Hanya nyalakan satu mode, matikan mode yang tidak inginkan dengan cara menambahkan tanda pagar pada bagian depan.

## Install Dependencies 

Install dependencies yang diperlukan dengan cara membuka text editor lalu jalankan perintah `composer install` dan tunggu hingga prosesnya selesai

## Informasi Database

- Database Name: `dbmanajemensapra`

## Migration

Buka terminal text editor lalu jalankan perintah `php spark migrate`

## Seeder

Buka terminal text editor lalu jalankan perintah `php spark db:seed <nama seeder>`
| Nama Seed                | Fungsi                                                                                                       |
| ------------------------ | -------------------------------------------------------------------------------------------------------------|
| UserSeeder               | Register akun Super Admin, Admin IT, Admin Sarpra dan Laboran                                                |
| IdentitasKelasSeeder     | Input identitas kelas karena untuk tabel identitas kelas **idIdentitasKelas 1** harus merupakan “Karyawan”   |    

## Run Project

Buka terminal text editor lalu jalankan perintah `php spark serve`

## Login Credentials

| Username       | Password      | Role          |
| -------------- | ------------- | ------------- |
| Super Admin    | superadmin    | Super Admin   |
| Laboran        | laboran       | Laboran       |
| Admin IT       | adminit       | Admin IT      |
| Admin Sarpra   | adminsarpra   | Admin Sarpra  |

## Akun Siswa dan Karyawan

Proses pembuatan akun siswa dan karyawan dalam sistem disederhanakan melalui halaman input **data siswa** dan **data pegawai**. Dengan cara ini, setiap input data secara otomatis menghasilkan pembuatan akun dengan role **user**.
| Data        | Username      | Password      |
| ----------  | ------------- | ------------- |
| Siswa       | NIS           | NIS           |
| Karyawan    | NIP           | NIP           |

Setelah login dengan username dan password yang telah dibuat, pengguna dapat dengan mudah mengganti passwordnya melalui halaman **profil pengguna**.

## Perbedaan Hak Akses

| Feature                    | Super Admin | Admin Sarpra | Admin IT | Admin Lab | User |
|----------------------------|-------------|--------------|----------|-----------|------|
| Dashboard                  | X           | X            | X        | X         | X    |
| **SARANA**                     |
| Data General               | X           | X            |          |           |      |
| Data Rincian Aset          | X           | X            |          |           |      |
| Pemusnahan Aset            | X           | X            |          |           |      |
| Request Peminjaman         | X           | X            |          |          |  X    |
| Data Peminjaman            | X           | X            |          |          |  X    |
| Pengajuan Peminjaman       | X           | X            |          |          |  X    |
| Layanan Aset               | X           | X            |          |           |      |
| Layanan Non Aset           | X           | X            |          |           |      |
| Data Pengaduan             | X           | X            |          |          |  X    |
| Data Umpan Balik           | X           | X            |          |        |  X    |
| Non-Inventaris             | X           | X            |          |           |      |
| **PRASARANA**                |
| Ruangan                    | X           | X            |          |           |      |
| Non Ruangan                | X           | X            |          |           |      |
| **LABORATORIUM**                |
| Laboratorium               | X           |              |          | X         |      |
| Data General               | X           |              |          | X         |      |
| Data Rincian Aset          | X           |              |          | X         |      |
| Pemusnahan Aset            | X           |              |          | X         |      |
| Request Peminjaman         | X           |              |          | X         | X    |
| Data Peminjaman            | X           |              |          | X         | X    |
| Pengajuan Peminjaman       | X           |              |          | X         | X    |
| Layanan Aset               | X           |              |          | X         |      |
| Layanan Non Aset           | X           |              |          | X         |      |
| **IT**      |
| Data General               | X           |              | X        |           |      |
| Data Rincian Aset          | X           |              | X        |           |      |
| Pemusnahan Aset            | X           |              | X        |           |      |
| Layanan Perangkat IT       | X           |              | X        |           |      |
| Aplikasi                    | X           |              | X        |           |      |
| Sosial Media               | X           |              | X        |           |      |
| Website                    | X           |              | X        |           |      |
| **SEKOLAH**     |
| Profil Sekolah              | X           | X             |          |           |      |
| Tagihan Air                 | X           | X             |          |           |      |
| Tagihan Listrik             | X           | X             |          |           |      |
| Tagihan Internet            | X           | X             |          |           |      |
| **MASTER**     |
| Data User                  | X           |              |          |           |      |
| User Logs                  | X           |              |          |           |      |
| User Actions               | X           |              |          |           |      |
| Backup                     | X           |              |          |           |      |
| Restore                    | X           |              |          |           |      |
| Data Siswa                 | X           |              |          |           |      |
| Data Pegawai               | X           |              |          |           |      |
| Pertanyaan Pengaduan       | X           |              |          |           |      |
| Pertanyaan Feedback         | X           |              |          |           |      |
| Identitas Gedung           | X           |              |          |           |      |
| Identitas Lantai           | X           |              |          |           |      |
| Identitas Prasarana        | X           |              |          |           |      |
| Identitas Laboratorium     | X           |              |          |           |      |
| Identitas Sarana           | X           |              |          |           |      |
| Identitas Kelas            | X           |              |          |           |      |
| Non Inventaris             | X           |              |          |           |      |
| Kategori Barang            | X           |              |          |           |      |
| Kategori MEP               | X           |              |          |           |      |
| Sumber Dana                | X           |              |          |           |      |
| Status Layanan             | X           |              |          |           |      |



## System Launch
- PHP 8.0.28
- CodeIngiter 4.4.3
  
