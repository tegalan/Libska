<?php
session_start();
/*
File: ajax-list-pinjam.php
Fungsi: Peminjaman dengan form ajax
Auth: ShowCheap
*/
require '../sistem/config.php';
require '../sistem/siswa.php';
require '../sistem/class_buku.php';
cek_user();
sambung();
//inisialisasi data
$siswa=$_GET["siswa"];
//set param to get data siswa
$member=new siswa();
$member->setInduk($siswa);
//Set query daftar yang di pinjam
$sql=mysql_query("SELECT * FROM tbl_peminjaman WHERE siswa=\"$siswa\" AND kembali=\"0\"");
//buku belum kembali
$belum=mysql_num_rows($sql);

//jika fungsi mengembalikan satu satu
if($_GET["kembali"]=="satu"){
    $pinjam=$_GET["tgl_pinjam"];
    $kembaline=date('Y-m-d');
    $siswa=$_GET["siswa"];
    $buku=$_GET["buku"];
    
    $kmb=mysql_query("UPDATE tbl_peminjaman SET kembali='1', tgl_kembali='$kembaline' WHERE siswa='$siswa' && buku='$buku' && tgl_pinjam='$pinjam'");
    $buk=mysql_query("UPDATE tbl_buku SET status='1', peminjam='0' WHERE peminjam='$siswa' && kd_buku='$buku'");
    if($kmb && $buk){
        $obuku=new buku();
        $osiswa=new siswa();
        $obuku->setKode($buku);
        $osiswa->setInduk($siswa);
        $s=mysql_query("DELETE FROM tbl_telat WHERE buku='$buku'");
        if($s){
            //echo "hapused";
        }else{
            catat("Gagal Menghapus Jatuh Tempo ".$obuku->getJudul()." ".$osiswa->getNama());
        }
        catat($_SESSION['nama'],"Mengembalikan Buku ".$obuku->getJudul()." dari ".$osiswa->getNama());        
    } 
    exit(0);
}

//Jika fungsi mengembalikan semua
if($_GET["kembali"]=="all"){
    //inisialisasi data
    $kembaline=sekarang();
    $siswa=$_GET["siswa"];
    $osiswa=new siswa();
    $osiswa->setInduk($siswa);
    
    $kmb=mysql_query("UPDATE tbl_peminjaman SET kembali='1', tgl_kembali='$kembaline' WHERE siswa='$siswa' && kembali='0'");
    $buk=mysql_query("UPDATE tbl_buku SET status='1', peminjam='0' WHERE peminjam='$siswa'");
    //echo "UPDATE pinjaman SET kembali='1', kembaline='$kembaline' WHERE siswa='$siswa' && kembali='0' <br>";
    //echo "update buku set status='Ada', peminjam='0' where peminjam='$siswa' <br>";
    //$tempo=
    if($kmb && $buk){
        $c=mysql_query("DELETE FROM tbl_telat WHERE induk ='$siswa'");
        if($c){
            //echo "Di Busek";
        }else{
             catat("Gagal Menghapus Jatuh Tempo dari ".$osiswa->getNama());
        }
        catat($_SESSION['nama'],"Mengembalikan Semua Buku dari ".$osiswa->getNama());
    }
    exit(0);
}

?>

<div class="well" style="padding: 10px;">
<a href="#" class="btn"><?php echo $member->getNama() ?></a>
<a id="detail" href="#" class="btn" rel="popover" data-toggle="button">Detail</a>
<script>
    $('#detail').popover({
    html : 'true',
    placement : 'right',
    title : '<?php echo $member->getNama() ?>',
    content : '<table cellpadding="3" cellspacing="3"><tr><td>No Anggota</td><td>:</td><td><?php echo $member->getInduk() ?></td></tr><tr><td>Kelas</td><td>:</td><td><?php echo $member->getKelas()." ".$member->getJurusan() ?></td></tr><tr><td>Belum Kembali</td><td>:</td><td><?php echo $member->getSedangMeminjam() ?></td></tr><tr><td>Semua Peminjaman</td><td>:</td><td><?php echo $member->getTotalMeminjam() ?></td></tr></table>'
});
</script>
</div>
<div style="height: 415px; overflow: auto; margin-bottom:10px; border: 1px solid #E3E3E3;">
<table width="100%" class="table table-striped table-hover">
    <thead style="font-weight:bold;">
        <tr>     
            <td>Kode</td>
            <td>Judul</td>
            <td>Tgl.Pinjam</td>
            <td>Jatuh Tempo</td>
            <td></td>
        </tr>
    </thead>
    <tbody id="tbody">
    <?php 
        $id=1; 
        $book=new buku();
        while($d=mysql_fetch_array($sql)){ 
            $book->setKode($d["buku"]);
    ?>
       <tr class="item" id="row-<?php echo $id; ?>">
            <td><?php echo $d["buku"]; ?></td>
            <td><?php echo $book->getJudul(); ?></td>
            <td><?php echo $d["tgl_pinjam"]; ?></td>
            <td><?php echo $d["tgl_tempo"]; ?></td>
            <td><a class="btn btn-primary" href="javascript:kembali(<?php echo "'".$id."','".$d["tgl_pinjam"]."','".$member->getInduk()."','".$d["buku"]."'"; ?>)">Kembali</a></td>
        </tr>
    <?php $id++; } ?>
    </tbody>
</table>
</div>
<a href="javascript:goAll(<?php echo "'".$member->getInduk()."'"; ?>)" class="btn btn-success">Kembali Semua</a>
<a href="#" class="btn btn-primary" data-dismiss="modal">Batal</a>