Libska
======

Simple Library Administration by Sucipto

Changelog V 3.1.0
=================

* Minor Bug Fix
* Merapihkan Database
* Fitur Upgrade dari versi lama.
* Import data Siswa / Anggota
* Import data Buku.

UPDATE 2015-05-25
=================
* Fix ajax-pinjam : memperbaiki file ajax-pinjam.php yang error pada saat menekan tombol pinjam.


Cara Install
============
Cara install cukup mudah, buat database dengan nama libska (atau sesuai keinginan anda)
update file configurasi di `sistem/config.php` sesuai pengaturan database anda.

```
function sambung($db='libska', $host='localhost', $user='root', $pass='root'){
    @mysql_connect($host,$user,$pass) or die('<strong style="color: red;">Gagal Terhubung ke database '.mysql_error().'</strong>');
    @mysql_select_db($db) or die('<strong style="color: red;">Gagal Memilih database</strong>');;
};
```
