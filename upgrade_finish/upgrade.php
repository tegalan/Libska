<?php

/*
 * @author :   Sucipto
 * @site   :   www.sucipto.net
 */

session_start();
include_once '../sistem/config.php';
sambung();
$mode=$_GET['upgrade'];
?>
<html lang="en">
<head>
    <title>Perpustakaan <?php echo get_sistem("nama"); ?> | LIBSKA <?php echo getVersion(); ?></title>
    <link href="../tampilan/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src='../tampilan/jq.js' type='text/javascript'></script>
    <script src="../tampilan/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <div class="well">
<?php 
if($mode=="" || $mode==null){
?>
            <h1>1. Upgrade Buku</h1>
            <p>Sistem secara otomatis akan memperbaruhi susunan tabel buku tanpa merubah record yang sudah ada.</p>
            <a href="?upgrade=buku" class="btn btn-success">Upgrade Tabel Buku >></a>
<?php
exit();
}elseif($mode=="buku"){
   //Buka tabel buku lama
    $sql_buku=  mysql_query("SELECT * FROM buku");
    //Kosongkan database baru
    mysql_query("TRUNCATE tbl_buku");
    //Buat Counter
    $berhasil=0;
    $gagal=0;
    while($data=  mysql_fetch_array($sql_buku)){
        //Data Buku Lama
        $no=  addslashes($data['no']);
        $kd_buku=  addslashes($data['kd_buku']);
        $judul=  addslashes($data['judul']);
        $pengarang=  addslashes($data['pengarang']);
        $thn_terbit=  addslashes($data['thn_terbit']);
        $penerbit=  addslashes($data['penerbit']);
        $harga=  addslashes($data['harga']);
        $status= $data['status']=="Ada"?"1":"0";
        $peminjam=  addslashes($data['peminjam']);
        $count=  addslashes($data['count_pinjam']);
       
        $transfer_sql="INSERT INTO tbl_buku SET no=\"$no\", kd_buku=\"$kd_buku\", judul=\"$judul\", pengarang=\"$pengarang\", thn_terbit=\"$thn_terbit\", penerbit=\"$penerbit\", harga=\"$harga\", status=\"$status\", peminjam=\"$peminjam\", count=\"$count\"";
        $transfer=mysql_query($transfer_sql);
        if($transfer){
            //echo "$no Berhasil di transfer<br>";
            $berhasil++;
        }else{
            echo "$no $kd_buku Gagal di transfer<br>";
            $gagal++;
        }
    }//endwhile
    echo "<h1>2. Upgrade Peminjaman</h1><p>Data buku berhasil di upgrade. Berhasil di transfer $berhasil item, Gagal di transfer $gagal item.</p>";
    if($berhasil!=0){
        echo "<a href='?upgrade=peminjaman' class='btn btn-success'>Upgrade Peminjaman</a>";
    }
    exit(0);
}elseif($mode=="peminjaman"){
    //Buka Peminjaman Lama
    $sql_peminjaman= mysql_query("SELECT * FROM pinjaman");
    //Kosongkan database baru
    mysql_query("TRUNCATE tbl_peminjaman");
    //Buat Counter
    $berhasil=0;
    $gagal=0;
    while($data=  mysql_fetch_array($sql_peminjaman)){
        //Data BUku Lama
        $id=  addslashes($data['id']);
        $siswa=  addslashes($data['siswa']);
        $buku=  addslashes($data['buku']);
        $tgl_pinjam=  tanggal($data['tgl_pinjam']);
        $tgl_tempo=  tanggal($data['tgl_kembali']);
        $tgl_kembali=  tanggal($data['kembaline']);
        $kembali=  addslashes($data['kembali']);
        $id_petugas= id_pustakawan($data['petugas']);
        //Sql Transfer
        $sql_transfer="INSERT INTO tbl_peminjaman SET id=\"$id\", siswa=\"$siswa\", buku=\"$buku\", tgl_pinjam=\"$tgl_pinjam\", tgl_tempo=\"$tgl_tempo\", tgl_kembali=\"$tgl_kembali\", kembali=\"$kembali\", id_petugas=\"$id_petugas\"";
        //echo tanggal($data['tgl_pinjam'])." ".date('d M Y',  strtotime(tanggal($data['tgl_pinjam'])))."<br>";
       //echo "$sql_transfer <br>"; 
        $transfer=  mysql_query($sql_transfer);
        if($transfer){
            //echo "$no Berhasil di transfer<br>";
            $berhasil++;
        }else{
            echo "$id Gagal di transfer<br>";
            $gagal++;
        }
    }//Endwhile
    echo "<h1>3. Upgrade Anggota (Siswa)</h1><p>Data peminjaman berhasil di upgrade. Berhasil di transfer $berhasil item, Gagal di transfer $gagal item. Lanjutkan Proses?</p>";
    if($berhasil!=0){
        echo "<a href='?upgrade=anggota' class='btn btn-success'>Upgrade Anggota >></a>";
    }
    exit(0);
}elseif($mode=='anggota'){
    //Just Rename thok.. :D
    mysql_query("DROP TABLE IF EXISTS tbl_anggota");
    $sql_first=  mysql_query("RENAME TABLE siswa TO tbl_anggota");
    $sql_second = mysql_query("ALTER TABLE tbl_anggota CHANGE count_pinjam count INT(11) NOT NULL DEFAULT '0'");
    if($sql_first){
        if(!$sql_second){
            //echo "ERROR QUERY 2 : change count_pinjam to count";
            $berhasil.="ERROR QUERY 2 : change count_pinjam to count ".mysql_error();
            if(mysql_query("ALTER TABLE `tbl_anggota` ADD `alumni` TINYINT NOT NULL AFTER `jurusan`")){
                //Done
            }else{
                echo mysql_error();
            }
        }else{
            $berhasil.="Berhasil";
        }
    }else{
        $berhasil= "ERROR QUERY 1: rename table ".mysql_error();
    }
    echo "<h1>4. Upgrade Sistem</h1><p>Data Siswa berhasil di upgrade.<br> Status : $berhasil <br> Lanjutkan Proses?</p>";
    if($berhasil=="Berhasil"){
        echo "<a href='?upgrade=sistem' class='btn btn-success'>Upgrade Sistem >></a>";
    }
    exit(0);
}elseif($mode=='sistem'){
    echo "<h1>5. Selesai</h1>";
    $status=0;
    //**Tabel Config
    if(mysql_query("DROP TABLE IF EXISTS tbl_config")){
        if(mysql_query("RENAME TABLE sistem TO tbl_config")){
            echo "Success Convert Config Table<br>";
            $status++;
        }else{
            echo "Unable to Rename table config<br>";
        }
    }else{
        echo "Unable To Drop Config Table<br>";
    }
    //**Tabel Anggota
    if(mysql_query("DROP TABLE IF EXISTS tbl_pustakawan")){
        if(mysql_query("RENAME TABLE anggota TO tbl_pustakawan")){
            echo "Berhasil Convert tabel pustakawan<br>";
            $status++;
        }else{
            echo "Gagal Convert tabel pustakawan<br>";
        }
    }else{
        echo "Gagal Drop tabel pustakawan<br>";
    }
    //**Tabel Kas
    $query=mysql_query("SELECT * FROM kas");
    
    //Buat Counter
    $berhasil=0;
    $gagal=0;
    while($data=  mysql_fetch_array($query)){
        $id=$data['id'];
        $tgl=tanggal($data['tgl']);
        $ket= $data['ket'];
        $masuk=$data['masuk'];
        $keluar=$data['keluar'];
        $saldo=$data['saldo'];
        //Lets do the harlem shake.. :D
        $convert_sql="INSERT INTO tbl_kas SET id=\"$id\", tgl=\"$tgl\", ket=\"$ket\", masuk=\"$masuk\", keluar=\"$keluar\", saldo=\"$saldo\"";
        if(mysql_query($convert_sql)){
            $berhasil++;
        }else{
            //echo "Error Insert data to new table";
            $gagal++;
        }
    }//Endwhile
    echo "<p>Horreee.. !! Data Kas berhasil di upgrade. Berhasil di transfer $berhasil item, Gagal di transfer $gagal item. Selesai</p>";
    if($berhasil!=0){
        $finish_query=  mysql_query("DROP TABLE buku, kas, pinjaman,tempo");//Daffuk we
        if($finish_query){
            catat("SISTEM","Berhasil Upgrade ke versi ".getVersion());
            echo "<a href='?upgrade=finish' class='btn btn-success'>Selesai >></a>";
        }else{
            echo "Unable to drop old tables, do it manualy :D<br>". mysql_error();
        }
        
    }
    exit(0);
}elseif($mode=='finish'){
    echo "<h1>Horeee..</h1><p>Versi Libska berhasil di update, versi saat ini adalah ".  getVersion().".<br>Silahkan hapus / rename folder upgrade pada direktori utama :D.</p>";
    echo "<a href='../index.php?upgrade=selesai' class='btn'>Kembali Ke laptop >></a>";
}
?>            
        </div>
    </div>
</body>
</html>
<?php putus(); ?>