<?php
session_start();
/*
File: stat.php
Fungsi: File untuk menampilkan statistik keseluruah sistem.
Auth: ShowCheap
*/
/*Mengambil File konfigurasi*/
require 'sistem/config.php';
cek_user();
/*Mengambil Header*/
get_kepala();
?>
<div id='navigasi' width='100%'></div>
<?
/*Mengambil halaman statistik*/
require 'halaman/stat.php';
?>



<?php get_kaki(); ?>