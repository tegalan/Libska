<?php
session_start();
/*
File: peminjaman.php
Fungsi: Melihat daftar peminjaman.
Auth: ShowCheap
*/

require 'sistem/config.php';
require 'sistem/class_buku.php';
sambung();
get_kepala();
cek_user();



/*Ambil Perintah*/
$cari=trim($_GET['pencarian']);
$where=$_GET['where'];
$kd_buku=$_GET['kmb'];
if($where==''){
    $where='siswa';
}

$kmbl=$_GET['kembali'];


$kembaline=$_GET['kembali'];

if($kmbl==''){
    $kmbl='%%';
}
if($kmbl=='all'){
    $kmbl='%%';
}


//Pembatsan tgl bulan thun *update 26 april 2012
//Gawe variabel tglblnthn saiki
$wow=explode(' ',sekarang());
$dino= $wow['0'];
$sasi=$wow['1'];
$taon= $wow['2'];

if($_GET['tanggal']!=''){
    $t=mysql_real_escape_string($_GET['tanggal']);
    $tgu=$t;
}else{
    $t='';
}
if($_GET['bulan']!=''){
    $b=mysql_real_escape_string($_GET['bulan']);
}else{
    $b='';
}
if($_GET['tahun']!=''){
    $th=mysql_real_escape_string($_GET['tahun']);
}else{
    $th='';
}
$jajal="$th-$b-$t";
//End tglblnthn

$dari=mysql_query("SELECT * FROM tbl_peminjaman");
$dari=mysql_num_rows($dari);
$belum=mysql_query("SELECT * FROM tbl_peminjaman WHERE kembali='0'");
$belum=mysql_num_rows($belum);


/*Lunasi dendo*/
if($_GET['denda']=='lunas'){
    $hapus=mysql_query("UPDATE tbl_anggota SET denda='0' WHERE no_induk='$cari'");
    
    if($val!='0'){
        $val=$_GET['tot'];
        $skg=date("Y-m-d");
        $kas=new db();
        $sal_akhr=$kas->single("SELECT saldo FROM tbl_kas ORDER BY id DESC limit 0,1");
        $saldo=$sal_akhr+$val;
        $convert=get_nama($cari);
        mysql_query("INSERT INTO tbl_kas SET masuk = '$val', tgl = '$skg', ket='Denda dari $convert', saldo='$saldo'"); 
    }
    if($hapus){
        catat($_SESSION['nama'],"Denda Lunas dari $cari");
        echo "<script type='text/javascript'>alert('Denda Lunasss !!')</script>";
        echo "<script type='text/javascript'>window.location='peminjaman.php?pencarian=".$cari."'</script>";
    }
        
}elseif($_GET['denda']=='batal'){
    $hapus=mysql_query("UPDATE tbl_anggota SET denda='0' WHERE no_induk='$cari'");
    if($hapus){
        catat($_SESSION['nama'],"Membatalkan Denda dari $convert");
    }
}

/*Ngitung dendo*/

if($_POST['denda'] && $_POST['tempo'] != ''){
    $tempo=$_POST['tempo'];
    $sucip=sekarang();
    $value= denda($tempo, $sucip);
    $cval=substr($value,0,1);
    if($cval!='-'){
        mysql_query("UPDATE tbl_anggota SET denda ='$value' WHERE no_induk='$cari'");
        catat($_SESSION['nama'],"Set Denda $cari -> $value");
    }
}

/*Kembali Semua*/
$tgl_kembali=date("Y-m-d");
if(isset($_POST['semua'])){
    $kmb=mysql_query("UPDATE tbl_peminjaman SET kembali='1', tgl_kembali='$tgl_kembali' WHERE siswa='$cari' && kembali='0'");
    $buk=mysql_query("UPDATE tbl_buku SET status='1', peminjam='0' WHERE peminjam='$cari'");
    //$tempo=
    if($kmb && $buk){
        $c=mysql_query("DELETE FROM tbl_telat WHERE induk ='$cari'");
        if($c){
            //echo "Di Busek";
        }else{
            echo "Gagal Menghapus jatuh tempo";
        }
        catat($_SESSION['nama'],"Mengembalikan Semua Buku dari $cari");
                echo "<script type='text/javascript'>
        $(document).ready(function(){
                $(\"#pesan\").show();   
                $(\"#pesan\").html(\"Buku Sudah dikembalikan Semua\");
                setTimeout('tutup()',2000);
            });        
        function tutup(){
            $(\"#pesan\").slideUp('slow');
        }
        </script>";
    }
}
/*Kembali Satu2*/
if(isset($_GET['kmb'])){
    $tgl_kmbl=$_GET["tgl_pinjem"];
    $kmb=mysql_query("UPDATE tbl_peminjaman SET kembali='1', tgl_kembali='$tgl_kembali' WHERE siswa='$cari' && buku='$kd_buku' && tgl_pinjam='$tgl_kmbl'");
    $buk=mysql_query("UPDATE tbl_buku SET status='1', peminjam='0' WHERE peminjam='$cari' && kd_buku='$kd_buku'");
    //$tempo=
    if($kmb && $buk){
        $s=mysql_query("DELETE FROM tbl_telat WHERE buku='$kd_buku'");
        if($s){
            //echo "hapused";
        }else{
            echo "Gagal Menghapus Jatuh Tempo !!";
        }
        catat($_SESSION['nama'],"Mengembalikan Buku $kd_buku dari $cari ");        
    } 
}
/*Ambil Peminjam*/
if($cari != '' && $where=='siswa'){
    $pem=mysql_query("SELECT * FROM tbl_anggota WHERE no_induk = $cari");
    $si=mysql_fetch_array($pem);
    $hisi=mysql_num_rows($pem);
}

//**Page**//
$bates=$_GET['banyak'];
if($bates==''){
    $bates='25';
}
$bts= $bates;
$hal = $_GET['hal'];
if (!isset($hal)){
    $mulai=0;
}else{
    $mulai= $hal * $bts;
};
$sql=mysql_query("SELECT * FROM tbl_peminjaman WHERE $where LIKE '%$cari%' && kembali LIKE '$kmbl'");
$semua=mysql_query("SELECT * FROM tbl_peminjaman");
$semua=mysql_num_rows($semua);
$jumlah=mysql_num_rows($sql);
$jhal=ceil($jumlah/$bts);


/*Ambil Data Peminjaman*/

if($_GET['tanggal']=='' && $_GET['bulan']=='' && $_GET['tahun']==''){
$jajal='';
}elseif($_GET['tanggal']==''){
    $jajal=  trim("$th-$b");
}elseif($_GET['bulan']==''){
    $jajal=  trim("$th-".date('m')."-$t");
}

$sql=mysql_query("SELECT *, DATE_FORMAT( tgl_pinjam,  '%d %M %Y' ) AS format_pinjam, DATE_FORMAT( tgl_tempo,  '%d %M %Y' ) AS format_tempo, DATE_FORMAT( tgl_kembali,  '%d %M %Y' ) AS format_kembali FROM tbl_peminjaman WHERE $where LIKE '%$cari%' && kembali LIKE '$kmbl' && tgl_pinjam LIKE '%$jajal%' ORDER BY siswa ASC limit $mulai, $bts");
//echo "SELECT *, DATE_FORMAT( tgl_pinjam,  '%d %M %Y' ) AS format_pinjam, DATE_FORMAT( tgl_tempo,  '%d %M %Y' ) AS format_tempo, DATE_FORMAT( tgl_kembali,  '%d %M %Y' ) AS format_kembali FROM tbl_peminjaman WHERE $where LIKE '%$cari%' && kembali LIKE '$kmbl' && tgl_pinjam LIKE '%$jajal%' ORDER BY siswa ASC limit $mulai, $bts";
$ht=mysql_num_rows($sql);
//
$sqll=mysql_query("SELECT * FROM tbl_peminjaman WHERE $where LIKE '%$cari%' && kembali LIKE '$kmbl' && tgl_pinjam LIKE '%$jajal%' ORDER BY siswa");
$ht2=mysql_num_rows($sqll);

?>
<script type="text/javascript" src="ajax/ajax-list-pinjam.js"></script>
<script type='text/javascript'>
  function kirim(){
    document.forms['frm-peminjaman'].submit();
  }
</script>
<!--modal popup peminjaman-->
<div style="height: 640px; width: 900px; margin-left: -30%; margin-top: -320px;" class="modal hide fade" id="detail-pinjam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
    <h3>Peminjaman</h3>
  </div>
  <div class="modal-body" style="height: 800px; max-height: 530px;">
    <div id="content-pinjam" style="margin-botom: 3px;">
        
    </div>
  </div>
  <div class="modal-footer">
    
  </div>
  
</div>
<h2>Peminjaman</h2>
<div id='pesan' style='padding: 5px; text-align: center; background-color: #ebf2e6; font-size: 30; font-weight: bold;'><!--Tampilkan Pesan di sini--></div>

<table width='100%' cellpadding='2' cellspacing='1' border='0' id='peminjaman' style='border: 1px solid #DDD7BD; font-size: 12px;' class="table table-hover table-striped">
    <thead>    
    <tr>
        <td colspan='8'>
            <form id='cari' name='frm-peminjaman' style="margin: 5px auto;">
                <input onchange='kirim();' name='pencarian' type='text' value='<?php echo $cari; ?>'>
                <select name='where' onchange='kirim()' style="width: 100px">
                    <option value='siswa' <?php if($_GET['where']=='siswa'){ echo "selected='selected'"; } ?>>No Induk</option>
                    <option value='buku' <?php if($_GET['where']=='buku'){ echo "selected='selected'"; } ?>>Buku</option>
                </select>
                <select name='kembali' onchange='kirim()'  style="width: 150px">
                    <option value='all' <?php if($_GET['kembali']=='all'){ echo "selected='selected'";} ?>>Semua</option> 
                    <option value='0' <?php if($_GET['kembali']=='0'){ echo "selected='selected'";} ?>>Belum Kembali</option>
                    <option value='1' <?php if($_GET['kembali']=='1'){ echo "selected='selected'";} ?>>Kembali</option>
                </select>
                <select name='banyak' onchange='kirim()'  style="width: 70px">
                    <option value='5'>5</option>
                    <option value='15'>15</option>
                    <option value='25' selected='selected'>25</option>
                    <option value='30'>30</option>
                    <option value='40'>40</option>
                    <option value='60'>60</option>
                    <option value='100'>100</option>
                    <option value='200'>200</option>
                    <option value='500'>500</option>
                    <option value='1000'>1000</option>
                </select>
                Tgl Pinjam:
                <select name='tanggal' onchange='kirim()'  style="width: 60px">
                <option value=''>Tgl</option>
                    <?php
                        for($t='1'; $t<='32'; $t++){
                            $selected='';
                            $tg=sprintf("%02s", $t);
                            if($_GET['tanggal']==$tg){
                                //echo $_GET['tanggal'];
                                
                                $selected="selected='selected'";
                            }
                            echo "<option value='$tg' $selected>$tg</option>";
                        }
                    ?>
                </select>
                <select name='bulan' onchange='kirim()'  style="width: 120px">
                    <option value=''>Bulan</option>
                    <option value="01" <?php if($_GET['bulan']=='01'){ echo "selected='selected'";} ?>>Januari</option>
                    <option value="02" <?php if($_GET['bulan']=='02'){ echo "selected='selected'";} ?>>Pebruari</option>
                    <option value="03" <?php if($_GET['bulan']=='03'){ echo "selected='selected'";} ?>>Maret</option>
                    <option value="04" <?php if($_GET['bulan']=='04'){ echo "selected='selected'";} ?>>April</option>
                    <option value="05" <?php if($_GET['bulan']=='05'){ echo "selected='selected'";} ?>>Mei</option>
                    <option value="06" <?php if($_GET['bulan']=='06'){ echo "selected='selected'";} ?>>Juni</option>
                    <option value="07" <?php if($_GET['bulan']=='07'){ echo "selected='selected'";} ?>>Juli</option>
                    <option value="08" <?php if($_GET['bulan']=='08'){ echo "selected='selected'";} ?>>Agustus</option>
                    <option value="09" <?php if($_GET['bulan']=='09'){ echo "selected='selected'";} ?>>September</option>
                    <option value="10" <?php if($_GET['bulan']=='10'){ echo "selected='selected'";} ?>>Oktober</option>
                    <option value="11" <?php if($_GET['bulan']=='11'){ echo "selected='selected'";} ?>>Nopember</option>
                    <option value="12" <?php if($_GET['bulan']=='12'){ echo "selected='selected'";} ?>>Desember</option>
                </select>
                <select name='tahun' onchange='kirim()'  style="width: 100px">
                <option value=''>Tahun</option>
                <?php
                    $sekarang=substr(sekarang(),-4);
                    for($i=2011; $i<=$sekarang; $i++){
                        $select='';
                            if(!isset($_GET['tahun'])){
                                if($i==$sekarang){
                                    $select='';//"selected='selected'";
                                }
                            }else{
                                $tahu=$_GET['tahun'];
                                if($i==$tahu){
                                    $select='selected="selected"';
                                }
                            }
                        echo "<option value='$i' $select>$i</option> ";   
                    }
                    ?>
                </select>
                <input type='submit' value='OK' class="btn btn-primary" style="margin-top: -8px;">
            </form>            
        </td>
    </tr>
    <tr align='center'>
        <td width='30'>No.</td>
        <td width='50'>Peminjam</td>
        <td>Kode</td>
        <td>Judul</td>
        <td>Tgl. Pinjam</td>
        <td>Jatuh Tempo</td>
        <td>Tgl. Kembali</td>
        <td>Status</td> 
    </tr>
    </thead>
    <?php $book=new buku(); ?>
    <?php $i=1; $kembali=0; while($p=mysql_fetch_array($sql)){
        $book->setKode($p['buku']);
        
        if($p['kembali']==0){
            $kembali++;
        }
    ?>  
        <tr>
            <td><?php echo $i ?></td>
            <td><a class="list" href='#' onclick='window.location="?pencarian=<?php echo $p['siswa']; ?>&where=siswa&kembali=all<?php //echo $kembaline; ?>"' title='Klik Untuk Detail'><?php echo $p['siswa']; ?></a></td>
            <td><a class="list" onclick='window.location="?pencarian=<?php echo $p['siswa']; ?>&where=siswa&kembali=all<?php //echo $kembaline; ?>"' href="#"><?php echo $p['buku']; ?></a></td>
            <td><a class="list" onclick='window.location="?pencarian=<?php echo $p['siswa']; ?>&where=siswa&kembali=all<?php //echo $kembaline; ?>"' href="#"><?php echo $book->getJudul(); ?></a></td>
            <td><?php echo $p['format_pinjam']; ?></td>
            <td><b><?php echo $p['format_tempo']; ?></b></td>
            <td><?php echo $p['tgl_kembali']=="0000-00-00" ? "-": $p['format_kembali']?></td>
            <td><?php echo $p['kembali']==0?"<span class='label label-important'>Belum Kembali</span>":"<span class='label label-info'>Sudah Kembali</span>";?></td>
                   
        </tr>
    <?php $i++; } ?>
    <?php if($hisi=='1' && $kembali!=0 && $ht!='0'){ //jika siswa di temukan 1?>
        <script type="text/javascript">$('#detail-pinjam').modal('show');</script>
        <script type="text/javascript">
                   $.ajax({
                       url:'ajax/ajax-list-pinjam.php?siswa=<?php echo $cari ?>',
                       type: 'POST',
                       data: $(this).serialize(),
                       success:function(data){
                           $("#content-pinjam").html(data);
                       }
                   })
       </script>
    <?php } ?>
    <?php
        if($ht=='0'){
            echo "<tr><td colspan='8' style='background-color: pink;'>Data tidak ditemukan, mohon periksa kembali pencarian</td></tr>";
        }
        
        
        ?>
    <tr>
        <td colspan='8' class="alert alert-error">Menampilkan : <b><?php echo $ht; ?></b> | Belum Kembali :  <b><?php echo $belum; ?></b> | Total Data : <b><?php echo $dari; ?></b></td>
    </tr>
    <tr>
        <td colspan='8'>         
            <ul class="pager">
                <li class="previous">
                    <a style='text-decoration: none;' href='<?php echo "?where=$where&pencarian=$cari&kembali=$kembaline&banyak=$bates&hal=".max($hal-1, 0); ?>'> &larr; Sebelumnya </a>
                </li>
                <li class="next">
                  <a style='text-decoration: none;' href='<?php echo "?where=$where&pencarian=$cari&kembali=$kembaline&banyak=$bates&hal=".min($hal+1, $jhal-1); ?>'>Selanjutnya &rarr;</a>
                </li>
            </ul>
        </td>
    </tr>
</table>

<?php get_kaki(); ?>
