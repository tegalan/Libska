<?php
session_start();
/*
File: exs.php
Fungsi: File untuk Export ke format exel.
Auth: ShowCheap
*/

require '../sistem/config.php';

if($_GET['mode']=='dwn'){
$tabel=$_GET['tabel'];
sambung();
$xl=new exel();
$xl->setNama("Libska-$tabel");
$a=$xl->doExport("select * from $tabel");
}
?>
