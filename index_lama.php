<?php
session_start();
/*
File: index.php
Fungsi: Resepsionis :D
Auth: ShowCheap
*/
include 'sistem/config.php';
//include 'run.php';
sambung();
get_kepala();
cek_user();
if(isset($_GET['stat'])){
    include 'halaman/stat.php';
    get_kaki();
    exit();
}

?>
<style>
   #pop:hover{
    background-color: #CCFFFF;
    border-bottom-color: red;
    border-bottom: 1px dotted;
    font-style: italic;
   }
   #pop{
    background-color: #CCCCFF;
   }
   #popl{
    font-size: 12px;
    background-color: #99FFFF;
   }
</style>
<script type='text/javascript'>
    $(document).ready(function(){
        $.ajax({
            url:'run.php',
            success: function(data){
                $("#res").html(data)
            }
            })
        
        })
</script>
<table width='100%' cellpadding='2' cellspacing='2' border='0'><!--Tabel Induk-->
    <tr>
        <td width='50%'><b>Buku Top 50</b>
            <table width='100%' cellpadding='2' cellspacing='1' border='0' style='border: 1px inset;' id='popl'><!--Tabel anak-->
            <tr style='background-color: #336666; color: #FFFFCC;' align='center'>
                <td>No</td><td>Judul Buku</td><td>Status</td><td>Di Pinjam</td>
            </tr>
            <?php $popu=mysql_query("select * from buku where count_pinjam != '0' order by count_pinjam DESC limit 0,50"); $no='1'; while($pop=mysql_fetch_array($popu)){ ?>
                <tr id='pop'>
                    <td><?php echo $no; ?></td><td><?php echo $pop['judul']; ?></td><td><?php echo $pop['status']; ?></td><td><?php echo $pop['count_pinjam']; ?> Kali</td>
                </tr>
             <?php $no++; } ?>   
            </table><!--Tabel anak-->   
        </td><!--TD Untuk Kolom Kedua-->
        <td valign='top'><b>Peminjam Aktif</b>
            <table width='100%' cellpadding='2' cellspacing='1' style='background-color: #FFFF99; font-size: 12px;'><!--Tabel anak2--> 
                <tr style='background-color: lightgreen;' align='center'>
                    <td>No</td><td>Nama</td><td>Peminjaman</td>
                </tr>
                <?php $i='1'; $ab=mysql_query("select * from siswa where count_pinjam !='0' order by count_pinjam DESC limit 0,50"); while($cde=mysql_fetch_array($ab)){ ?>
                    <tr style='background-color: #CCFF99;'>
                        <td><?php echo $i; ?></td><td><a title="Klik Untuk Melihat" href="peminjaman.php?pencarian=<?php echo $cde['no_induk']; ?>"><?php echo $cde['nama']; ?></a></td><td><?php echo $cde['count_pinjam']; ?> Kali</td>
                    </tr>
                    
                <?php $i++; } ?>
            </table><!--Tabel anak2--> 
        </td>
    </tr>
    <tr>
        <td colspan='2'><!--Tabel anak2.1--> 
<table width='100%' style='font-size: 12px;' cellspacing='1' cellpadding='2'>
<tr>
    <td colspan='4'><b>Daftar Buku Yang Telah Jatuh Tempo</b></td>
</tr>
<tr style='background-color: orange;' align='center'>
    <td>No</td><td>Kode Buku</td><td>Judul Buku</td><td>Peminjam</td><td>Tanggal</td>
</tr>
<?php
$jatuh=new db();
$saiki=sekarang();
$jatuh->sql("select * from tempo");
$jum=$jatuh->getJml();
if($jum !='0'){
    $noer=1;
    while($jatuh->hasil()){
        echo "<tr id='pop' title='Kode Buku ".$jatuh->hasil['buku']."'>";
        echo "<td>$noer</td>";
        echo "<td>".$jatuh->hasil['buku']."</td>";
        echo "<td>".$jatuh->hasil['judul']."</td>";
        echo "<td>".$jatuh->hasil['siswa']."</td>";
        echo "<td>".$jatuh->hasil['tanggal']."</td>";
        echo "</tr>";
        $noer++;
    }
}else{
    echo "<tr id='pop' align='center'><td colspan='5' id='res'>Tidak Ada</td></tr>";
    echo "<td colspan='5'><div style=\"background-color: #ebf2e6;\" id='reds'></div></tr>";
}
?>
<tr>
    <td colspan='5'><div style="background-color: #ebf2e6;" id='res'></div></tr>
</table>
        </td>
        
    </tr>

</table><!--Tabel Induk-->
<hr>
<marquee>Selamat Datang <?php echo $_SESSION['nama']; ?> | Perpustakaan <?php echo get_sistem("nama"); ?> | <?php echo get_sistem("alamat"); ?> | <?php echo get_sistem("web"); ?></marquee>
<hr>
<?
get_kaki();
?>