<?php
session_start();
echo "peminjam ".$_POST["peminjam"];
/*
File: ajax-pinjam.php
Fungsi: Peminjaman dengan form ajax
Auth: ShowCheap
*/
require '../sistem/config.php';
cek_user();
sambung();
/*Bukuuuu*/
$kd=$_POST['kd_buku'];
$bok=new db();
$bok->sql("select * from buku where kd_buku = '$kd'");
$bok->hasil();
$judul=$bok->hasil['judul'];
$judul=mysql_real_escape_string($judul);

/*Peminjam*/
$ind=$_POST['siswa'];
$stud=new db();
$stud->sql("select * from siswa where no_induk='$ind'");
$stud->hasil();

/*Input data*/
$tempo=$_POST['kembali'];
$sek=sekarang();
$ptgs=$_SESSION['nama'];
$kali=$bok->hasil['count_pinjam']+1;
$csis=$stud->hasil['count_pinjam']+1;
//echo "Kali".$kali."<br> Csis".$csis;
$namane=$stud->hasil['nama'];

$lebokne=mysql_query("insert into pinjaman set siswa='$ind', buku='$kd', judul='$judul', tgl_kembali='$tempo', tgl_pinjam='$sek', petugas='$ptgs'");
    if($lebokne){
        //echo "step 1";
        $bbbb=mysql_query("update buku set status='Kosong',peminjam='$ind', count_pinjam='$kali' where kd_buku='$kd'");
            if($bbbb){
                //echo "step 2";
                $catet=mysql_query("update siswa set count_pinjam='$csis' where no_induk='$ind'");
                catat($_SESSION['nama'], "Melayani $namane meminjam $judul");
                if($catet){
                    echo "<center><img src='tampilan/gambar/lodeng.gif' align='center'></center>";
                    //echo "<script type='text/javascript'>window.location.reload(true);</script>";
                }else{
                    echo "Errorrrr........";
                }
            }
    }
?>
