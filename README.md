<p align="center">
<img src="img/logo-jti.png" width="200">
</p>

<h1 align="center"> JTI Website</h1>

> Unofficial JTI Polinema Website.

## Memulai

**Import MySql database**

Import file database `database/jti_website.sql` ke PhpMyAdmin atau mysql host anda

**Setting koneksi database**

1. Copy file `config/constant.php.example` dan paste di dalam folder yang sama
2. Rename file baru hasil copy/paste tersebut menjadi `constant.php` <br> \*\* **jangan pernah menghapus, rename atau mengedit file constant.php.example**
3. Edit file `constant.php` tersebut, sesuaikan dengan koneksi database anda!

Contoh:

```php
$dbUrl  = "localhost"; // MySql host Url
$dbUser = "root"; // user Mysql
$dbPass = ""; // password Mysql
$dbName = "jti_website"; // nama database
```

Save & selesai!

<hr>

## Susunan File

- Susunan modul berdasarkan halaman sesuai user level
- Folder modul untuk file halaman / tampilan
- File setiap halaman, diinclude-kan ke modul/index.php sesuai nama halaman & hak akses
- Susunan process berdasarkan tabel pada database

```
jti-website/
├── css/
├── img/
├── js/
├── attachment/
│   ├── file/
│   └── img/
├── config/
│   ├── connection.php
|   ├── scripts.php
│   └── styles.php
├── database/
│   └── jti_website.sql
├── module/
|   ├── mahasiswa
|   |   ├── home.php
|   |   ├── setting.php
|   |   ├── kompen.php
|   |   ├── notifikasi.php
|   |   └── absen.php
|   ├── dosen/
|   |   ├── home.php
|   |   ├── notifikasi.php
|   |   └── setting.php
|   ├── admin/
|   |   ├── home.php
|   |   ├── notifikasi.php
|   |   ├── setting.php
|   |   ├── mahasiswa/
|   |   |   ├── tampilData.php
|   |   |   ├── tambah.php
|   |   |   └── update.php
|   |   ├── dosen/
|   |   |   ├── tampilData.php
|   |   |   ├── tambah.php
|   |   |   └── update.php
|   |   ├── kelas/
|   |   |   ├── tampilData.php
|   |   |   ├── tambah.php
|   |   |   └── update.php
|   |   ├── info/
|   |   |   ├── tampilData.php
|   |   |   ├── tambah.php
|   |   |   └── update.php
|   |   ├── jadwal/
|   |   |   ├── tampilData.php
|   |   |   ├── tambah.php
|   |   |   └── update.php
|   |   ├── chat/
|   |   |   ├── tampilData.php
|   |   |   ├── tambah.php
|   |   |   └── update.php
|   |   └── prodi/
|   |       ├── tampilData.php
|   |       ├── tambah.php
|   |       └── update.php
|   ├── index.php
|   └── login.php
├── process/
|   ├── login.php
|   ├── logout.php
|   ├── CRUD_mahasiswa.php
|   ├── CRUD_dosen.php
|   ├── CRUD_kelas.php
|   ├── CRUD_info.php
|   ├── CRUD_jadwal.php
|   ├── CRUD_chat.php
|   └── CRUD_prodi.php
└── index.php
```
