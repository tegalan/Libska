<?php
session_start();
/*
File: ajax-pinjam.php
Fungsi: Peminjaman dengan form ajax
Auth: ShowCheap
*/
require '../sistem/config.php';
require '../sistem/siswa.php';
require '../sistem/class_buku.php';
cek_user();
sambung();
/*Tanggal*/
 $hari=date('d');
    $bulan=date('M');
    $tahun=date('Y');
    $indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
    $bln=$indo[$bulan+1];
    $hari_ini=sekarang();//"$hari $bln $tahun";
    //**tgl kembali**//
    $waktu = date("d-n-Y", mktime(0,0,0,date("m"),date("d")+get_sistem("lama"),date("Y")));
    $x=explode('-', $waktu);
    $hari2=$x['0']; 
    $bulan2=$x['1'];
    $tahun2=date('Y');
    $bln2=$indo[$bulan2-1];
    //**waktu kembali yg sudah fix**//
    $kembali="$hari2 $bln2 $tahun2";
//************************************************NEW VERSION****************************//
$tgl_kembali = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+get_sistem("lama"),date("Y")));
$tgl_pinjam =   date("Y-m-d");

$buku=$_GET["buku"];
//echo "SIswa akan meminjam buku $buku <br>";
$siswa=new siswa();
$buku=new buku();
$buku->setKode($_GET["buku"]);

/*******Simpan Peminjaman**************/
if($_GET["mode"]=="pinjam"){
    $dsiswa=$_GET["siswa"];
    $dbuku=$_GET["buku"];
    $kembali=$_GET["kembali"];
    //Inisialisasi data siswa
    $cSiswa=new siswa();
    $cSiswa->setInduk($dsiswa);
    $meminjam=$cSiswa->getMeminjam()+1;
    //Inisialisasi data buku
    $cBuku=new buku();
    $cBuku->setKode($dbuku);
    $count=$cBuku->getCount()+1;
    //Petugas
    $ptgs=$_SESSION['uid'];
    //Sql Query
    $peminjaman=mysql_query("INSERT INTO tbl_peminjaman SET siswa=\"$dsiswa\", buku=\"$dbuku\", tgl_tempo=\"$tgl_kembali\", tgl_pinjam=\"$tgl_pinjam\", id_petugas=\"$ptgs\""); 
    //Debug
//    echo "INSERT INTO tbl_peminjaman SET siswa=\"$dsiswa\", buku=\"$dbuku\", tgl_kembali=\"2013-01-01\", tgl_pinjam=\"2013-01-01\", id_petugas=\"99\"";
    if($peminjaman){
        $buku=mysql_query("UPDATE tbl_buku SET status=\"0\", peminjam=\"$dsiswa\", count=\"$count\" WHERE kd_buku=\"$dbuku\"");
        if($buku){
            $qSiswa=mysql_query("UPDATE tbl_anggota SET count=\"$meminjam\" WHERE no_induk=\"$dsiswa\"");
            if($qSiswa){
                catat(get_pustakawan($ptgs), "Melayani ".$cSiswa->getNama()." meminjam ".$cBuku->getJudul());
                echo "<script>$('#pop-pinjam').modal('hide');</script>";
                echo "<script type='text/javascript'>window.location.reload(true);</script>";
            }else{
                echo "<div class=\"alert alert-error\">Terjadi kesalahan saat proses meminjam buku</div>";
            }
        }
    }
    //Stop eksekusi kode di bawah
    exit(0);
}//ENd Mode Pinjam
/*******Simpan Peminjaman**************/
?>

<script type="text/javascript">
    $("#frm-siswa").submit(function(){
            $.ajax({
                url:'ajax/ajax-pinjam.php?buku=<?php echo $_GET["buku"] ?>',
                type: 'POST',
                data: $(this).serialize(),
                success:function(data){
                    $("#content").html(data);
                }
            })
        })
</script>

<div id="content">
<form name="form-siswa" onsubmit="return false" id="frm-siswa">
    <input id="cari-siswa" type="text" name="siswa" placeholder="Masukkan No Anggota" style="width: 85%;" value="<?php echo $_POST["siswa"];  ?>">
    <input type="submit" value="Cari" class="btn btn-primary" style="margin-top: -10px;">
</form>
<?php

if($_POST["siswa"] or $_GET["siswa"]){
    
    $siswa->setInduk($_POST["siswa"]);
?>
<?php  if($siswa->cekAda()){ ?>
<h4>Peminjam</h4>
<table cellpadding="3" class="well" width="100%">
    <tr>
        <td width="100">Nama</td><td width="2">:</td><td><?php echo $siswa->getNama(); ?></td>
    </tr>
    <tr>
        <td>Kelas /  Jurusan</td><td>:</td><td><?php echo $siswa->getKelas()." ".$siswa->getJurusan(); ?></td>
    </tr> 
</table>
<?php
$siswa_ada=true;
}else{ ?>
<div class="alert alert-error">No Anggota tidak di temukan, silahkan di check kembali</div>
<?php
$siswa_ada=false;
} ?>
<h4>Buku</h4>
<table cellpadding="3" class="well" width="100%">
<tr>
    <td width="100">Kode Buku</td><td width="2">:</td><td><?php echo $buku->getKode(); ?></td>
</tr>
<tr>
    <td>Judul</td><td>:</td><td><?php echo $buku->getJudul(); ?></td>
</tr>
<tr>
    <td>Pengarang</td><td>:</td><td><?php echo $buku->getPengarang(); ?></td>
</tr>
<tr>
    <td>Di Pinjam</td><td>:</td><td><?php echo $buku->getCount(); ?></td>
</tr>
</table>
<h4>Periode</h4>
<div class="well">
    <?php echo "<b>".sekarang()."</b> Sampai <b>".$kembali."</b>"; ?>
</div>
<?php
if($siswa_ada){
?>
<script type="text/javascript">
    $('#tmbl-pinjam').focus();
    $('#tmbl-pinjam').click(function(){
        $.ajax({
                url:'ajax/ajax-pinjam.php?mode=pinjam&buku=<?php echo $_GET["buku"] ?>&siswa=<?php echo $siswa->getInduk(); ?>&kembali=<?php echo $kembali; ?>',
                type: 'POST',
                success:function(data){
                    $("#content").html(data);
                }
            })
    })
</script>
<a href="#" class="btn btn-primary" id="tmbl-pinjam">Pinjam</a> <a class="btn" href="#" data-dismiss="modal" aria-hidden="true">Batal</a>
<?
}
?>
<?php }else{ ?>
<script type='text/javascript'>
    $("#pop-pinjam").on('shown',function(){
        $('input[name="siswa"]').focus();
    })
    
</script>
<?php
}; //end if $_POST["siswa"] 
?>
</div><!--div content-->
