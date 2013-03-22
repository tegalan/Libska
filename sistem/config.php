<?php
/*
File: config.php
Fungsi: Koneksi ke Database
Auth: ShowCheap
*/
error_reporting('0');
require 'fungsi.php';
function sambung($db='libska', $host='localhost', $user='root', $pass='root'){
    @mysql_connect($host,$user,$pass) or die('<strong style="color: red;">Gagal Terhubung ke database '.mysql_error().'</strong>');
    @mysql_select_db($db) or die('<strong style="color: red;">Gagal Memilih database</strong>');;
};

function putus(){
    mysql_close;
}
?>
