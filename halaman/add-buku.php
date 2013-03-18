<?php
/*
File: add-buku.php
Fungsi: File untuk inMenambahkan buku ke database.
Auth: ShowCheap
*/


/*Jika Yang login adalah admin*/
if($_SESSION['level']=='Admin'){
    //**Sejenis**//
    $kbuk=$_POST['kd_buku'];
    $sejenis=explode('/',$kbuk);
    //$not=$m['kd_buku'];
    $satu=$sejenis['0'];
    $dua=$sejenis['1'];
    $tiga=$sejenis['2'];
     
    $tiga=substr($tiga,0,2);
   
    $sejenis2= "$satu/$dua/$tiga";
    //echo $sejenis2;
    sambung();
    $sql=mysql_query("SELECT * FROM tbl_buku WHERE kd_buku LIKE '$sejenis2%' ORDER BY kd_buku DESC");
    //$all=mysql_query("select * from buku where kd_buku like '$sejenis2%' && kd_buku not like '$not%'");
    $yang_sama=mysql_num_rows($sql);
    //$all=mysql_num_rows($all);
    if($yang_sama != '0' && $_GET['tBuku']==''/*&& !isset($_SESSION['tBuku'])*/){
        
        $has=mysql_fetch_array($sql);
        $urutan=substr($has['kd_buku'],-3);
        echo "Ada Buku Yang Sekelompok dengan Kode <b>\"".$sejenis2."\"</b> Apakah Anda Ingin Melanjutkan?<br>";
        echo "<br><center><button onclick='direk();'>YA</button>  <button onclick='history.go(-1)'>TIDAK</button></center>";
        $hasil=$urutan+1;
        $urut_new= sprintf("%03s", $hasil);      
        echo "<script type='text/javascript'>function direk(){ window.location='buku.php?tBuku=".$sejenis2."&tambah=1'; }</script>";
        exit();
    }
    
    
    //Jika Submit Tambah
    if($_POST['tambah'] && $_POST['kd_buku']!=''){
        $kode=$_POST['kd_buku'];
        $jdl=mysql_real_escape_string($_POST['judul']);
        $pngrng=mysql_real_escape_string($_POST['pengarang']);
        $thn=$_POST['tahun'];
        $pnrbt=mysql_real_escape_string($_POST['penerbit']);
        $hrg=$_POST['harga'];
        sambung();
        $sql=mysql_query("INSERT INTO tbl_buku SET kd_buku='$kode', judul='$jdl', pengarang='$pngrng', thn_terbit='$thn', penerbit='$pnrbt', harga='$hrg', status='1'");
        //echo "INSERT INTO tbl_buku SET kd_buku='$kode', judul='$jdl', pengarang='$pngrng', thn_terbit='$thn', penerbit='$pnrbt', harga='$hrg'";
        if($sql){
            catat($_SESSION['nama'],"Menambah Buku $jdl($kode)");
            echo "<script type='text/javascript'>alert('Berhasil di Simpan');</script>";
        }else{
            catat($_SESSION['nama'],"Gagal Menambah Buku $jdl");
            echo "<script type='text/javascript'>alert('Gagal Di Simpan');</script>";
        }
    }
    //jika hapus
    if($_GET['hapus']=='1'){
        $bukune=$_GET['buku'];
        $guak=mysql_query("DELETE FROM tbl_buku WHERE kd_buku='$bukune'");
        if($guak){
            catat($_SESSION['nama'],"Menghapus Buku $bukune");
            //header('location: buku.php');
            echo "<script type=\"text/javascript\">window.location='buku.php'</script>";
        }else{
            catat($_SESSION['nama'],"Gagal Menghapus Buku $bukune");
            echo "<script type='text/javascript'>alert('Buku Gagal di Hapus \n Mohon di chek kembali.');</script>";
        }
    };
    if(isset($_GET['tBuku'])){
        $bukunya=$_GET['tBuku'];
            $by=explode('/',$bukunya);
                $kod=substr($by[2],0,2);
                    $bukunya=$by[0]."/".$by[1]."/".$kod;
                    //echo $bukunya;
        $sq=mysql_query("SELECT * FROM tbl_buku WHERE kd_buku LIKE '$bukunya%' ORDER BY kd_buku DESC");
        $arr=mysql_fetch_array($sq);
        $scp=explode('/',$arr['kd_buku']);
        $kd=substr($scp[2],0,2);
        $akhir= substr($scp[2],-3);
            $akhir=$akhir+1;
                $akhir=sprintf("%03s",$akhir);
        $valKB=$scp[0]."/".$scp[1]."/".$kd.$akhir;
        //echo $arr['kd_buku'];
        $_SESSION['tBuku']=$valKB;
    }
?>

<script type='text/javascript'>
  function kirim(){
    document.forms['frm-tambah'].submit();
  }
</script>
<h2>Tambah Buku</h2>
<fieldset><legend>Input Informasi Buku</legend>
<form action='' method='post' name='frm-tambah'>
<table>
    <tr>
        <td>Kode Buku</td><td> : </td><td><input type='text' name='kd_buku' id='kb' value='<?php echo $valKB; ?>'></td>
    </tr>
     <tr>
        <td>Judul Buku</td><td> : </td><td><input class='input-xlarge' type='text' name='judul' size='40' value="<?php echo $arr['judul']; ?>"></td>
    </tr>
     <tr>
        <td>Pengarang</td><td> : </td><td><input class='input-xlarge' type='text' name='pengarang' value="<?php echo $arr['pengarang']; ?>"></td>
    </tr>
    <tr>
        <td>Tahun terbit</td><td> : </td><td><input  class='input-small' type='text' name='tahun' size='10' value='<?php echo $arr['thn_terbit']; ?>'></td>
    </tr>
    <tr>
        <td>Penerbit</td><td> : </td><td><input class='input-xlarge' type='text' name='penerbit' value="<?php echo $arr['penerbit']; ?>"></td>
    </tr>
    <tr>
        <td>Harga</td><td> : </td><td><input class='input-small' type='text' name='harga' size='10' value="<?php echo $arr['harga']; ?>"></td>
    </tr>
    <tr><td colspan='3'><input class="btn btn-info" type='submit' value='Tambah' name='tambah'></td></tr>
</table>
</form>
</fieldset>
<?php }else{
    echo "<p class='alert alert-error'>Maaf, anda tidak memiliki akses ke halaman ini.</p>";
}?>