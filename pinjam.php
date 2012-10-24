<?php
session_start();
/*
File: pinjam.php
Fungsi: Menambah peminjaman.
Auth: ShowCheap
*/

require 'sistem/config.php';
cek_user();
sambung();
if($_POST['tombol']){
$peminjam=mysql_real_escape_string($_POST['peminjam']);
$asdf=mysql_query("select * from siswa where no_induk='$peminjam'");
$cip=mysql_fetch_array($asdf);
$hitung=mysql_num_rows($asdf);
putus();
}

$no=$_GET['buku'];
sambung();
$buku=mysql_query("select * from buku where no='$no'");
$m=mysql_fetch_array($buku);
if($m['peminjam']!='0'){
    die("ERROR: Buku Sudah Dipinjam<script type='text/javascript'>alert('Buku Sudah Dipinjam'); window.close();</script>");
}
//**Sejenis**//
$sejenis=explode('/',$m["kd_buku"]);
$not=$m['kd_buku'];
$satu=$sejenis['0'];
$dua=$sejenis['1'];
$tiga=$sejenis['2'];
$tiga=substr($tiga,0,2);
$sejenis2= "$satu/$dua/$tiga";
$sql=mysql_query("select * from buku where kd_buku like '$sejenis2%' && kd_buku not like '$not%'  order by kd_buku ASC");
$all=mysql_query("select * from buku where kd_buku like '$sejenis2%' && kd_buku not like '$not%'");
$yang_sama=mysql_num_rows($sql);
$all=mysql_num_rows($all);
putus();
//**input pinjam**/
//**tgl kembali**//
    $indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
    $waktu = date("d-n-Y", mktime(0,0,0,date("m"),date("d")+14,date("Y")));
    $x=explode('-', $waktu);
    $hari2=$x['0']; 
    $bulan2=$x['1'];
    $tahun2=date('Y');
    $bln2=$indo[$bulan2-1];
    //**waktu kembali yg sudah fix**//
    $kembali="$hari2 $bln2 $tahun2";
if($_GET['mode']=='pinjam'){
    $sis=mysql_real_escape_string($_GET['siswa']);
    $nyilih= new db();
    $jml_pinjam= $nyilih->baris("select * from buku where peminjam = '$sis' && status='Kosong'");
    //Setting Batasan Peminjaman
    if($jml_pinjam >=6){
        echo "Siswa Ini Sudah Meminjam Sebanyak : ".$jml_pinjam." Buku yang belum kembali";
        
        echo "<script type='text/javascript'>alert('Melebihi batas maksimum pinjaman !');</script>";
        echo "<script type='text/javascript'>window.close();</script>";
        exit();
    }
    //**tgl pinjam**//
    $hari=date('d');
    $bulan=date('M');
    $tahun=date('Y');
    $indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
    $bln=$indo[$bulan+1];
    $hari_ini=sekarang();//"$hari $bln $tahun";
    //**tgl kembali**//
    $waktu = date("d-n-Y", mktime(0,0,0,date("m"),date("d")+14,date("Y")));
    $x=explode('-', $waktu);
    $hari2=$x['0']; 
    $bulan2=$x['1'];
    $tahun2=date('Y');
    $bln2=$indo[$bulan2-1];
    //**waktu kembali yg sudah fix**//
    $kembali="$hari2 $bln2 $tahun2";

    $bku=$m['kd_buku'];
    
    $kali=$m['count_pinjam']+1;
    $ptgs=$_SESSION['nama'];
    $judul=mysql_real_escape_string($m['judul']);
    $input=mysql_query("insert into pinjaman set siswa='$sis', buku='$bku', judul='$judul', tgl_pinjam='$hari_ini', tgl_kembali='$kembali', petugas='$ptgs'");
        if($input){
            $bbbb=mysql_query("update buku set status='Kosong',peminjam='$sis', count_pinjam='$kali' where kd_buku='$bku'");
            if($bbbb){
                /*Menambahkan count pinjam di database*/
                $xy=mysql_query("select * from siswa where no_induk='$sis'");
                    $yz=mysql_fetch_array($xy);
                        $tam=$yz['count_pinjam']+1;
                mysql_query("update siswa set count_pinjam='$tam' where no_induk='$sis'");
                catat($_SESSION['nama'], "Melayani $sis meminjam $judul");
                echo "<script type='text/javascript'>alert('Berhasil Dipinjam !');</script>";                
                echo "<script type='text/javascript'>opener.location.reload(true);</script>";
                echo "<script type='text/javascript'>window.close();</script>";
            }
        }
}

?>
<html>
<head>
    <title>Peminjaman</title>
    <style>
        body{
            background: url(tampilan/gambar/bg.png);
            
        }
        fieldset, body, legend, table{
            font-family: arial;
            font-size: 13px;
        }
        #copyright{
            font-style: italic;
            color: gray;
            font-size: 11px;
        }
        #isi{
            margin: 0 auto;
            width: 500;
            background-color: lightgreen;
            padding: 10px;
        }
        input[type='text']{
            background-color: orange;
        }
    </style>
    <script>
    function setFocus() {
      var loginForm = document.getElementById("pinjam");
      if (loginForm) {
        loginForm["peminjam"].focus();
      }
    }
    </script>
<?php
    if($_POST['tombol'] && $_POST['peminjam']!=='' && $hitung !='0'){
?>
<script type='text/javascript'>
        var tanya = confirm("Peminjam:\n No Induk: <?php echo $cip['no_induk']; ?>\n Nama: <?php echo $cip['nama']; ?>\n Kelas: <?php echo $cip['kelas']." ".$cip['jurusan']; ?>\n=========== ");
        function redir(){
                window.location='?mode=pinjam&buku=<?php echo $m['no']; ?>&siswa=<?php echo $_POST['peminjam']; ?>';
            }
        if (tanya){
            setTimeout('redir()',500);
        }
</script>
<?php } ?>
</head>
<body onload='setFocus();'>
<div id='slip'>
<div id='isi'>

<fieldset>
    <legend>Informasi Buku</legend>
    <table>
        <tr>
            <td>Kode Buku</td><td>:</td>
            <td><?php echo $m['kd_buku']; ?></td>
        </tr>
        <tr>
            <td>Judul Buku</td><td>:</td>
            <td><?php echo $m['judul']; ?></td>
        </tr>
        <tr>
            <td>Pengarang</td><td>:</td>
            <td><?php echo $m['pengarang']; ?></td>
        </tr>
        <tr>
            <td>Tahun Terbit</td><td>:</td>
            <td><?php echo $m['thn_terbit']; ?></td>
        </tr>
        <tr>
            <td>Penerbit</td><td>:</td>
            <td><?php echo $m['penerbit']; ?></td>
        </tr>
    </table>
</fieldset>

<fieldset>
    <legend>Buku yang sama</legend>
    <?php if($yang_sama != '0'){ ?>
    <table width='100%' cellpadding='3' cellspacing='1'>
        <tr style='background-color: orange;' align='center'>
            <td>No</td><td>Kode</td><td>Judul</td><td>Tahun</td><td>Status</td>
        </tr>
        <?php
        $nom=1;
        while($x=mysql_fetch_array($sql)){
           echo "
           <tr style='background-color: #FFFF99'>
            <td>$nom</td><td>".$x['kd_buku']."</td><td>".$x['judul']."</td><td>".$x['thn_terbit']."</td><td>".$x['status']."</td>
            </tr>
           ";
           $nom++;
        }
        ?>
    <tr>
        <td colspan='5'>Total Buku yang sama: <?php echo "$yang_sama dari $all"; ?></td>
    </tr>
    </table>
    <?php }else{
        echo "Tidak Ada Buku Yang Sama";
    } ?>
</fieldset>
<!------
<fieldset><legend>Peminjam</legend>
    <form action='' method='POST' id='pinjam'>
        <table>
            <tr>
                <td colspan='3'><p style='font-style: italic; color: gray;'>No Induk Siswa:</p> </td>
            </tr>
            <tr>
                <td><input type='text' id='peminjam' name='peminjam' value='<?php echo $_GET['peminjam']; ?>'></td>
                
            </tr>
            <tr><td>Tanggal Pinjam :</td></tr>
            <tr>
                
                <td><b><?php echo sekarang(); ?> - <?php echo "$kembali"; ?></b></td>
            </tr>
            <tr>
                <td colspan='3'><input type='submit' value='Pinjam' name='tombol'></td>
            </tr>
        </table>       
    </form>
</fieldset>-->
</div>
<?php get_kaki(); ?>
