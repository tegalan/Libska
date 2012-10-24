<?php
session_start();
/*
File: buku.php
Fungsi: menampilkan daftar buku.
Auth: ShowCheap
*/
require 'sistem/config.php';
@sambung();
get_kepala();
//*************Header************//
if($_GET['tambah']=='1'){
    require 'halaman/add-buku.php';
}elseif($_GET['tambah']!='1'){
require 'halaman/inc-buku.php';
}
//*************Footer************//
get_kaki();
?>