<?php
session_start();
/*
File: peminjaman.php
Fungsi: Melihat daftar peminjaman.
Auth: ShowCheap
*/

require 'sistem/config.php';
sambung();
get_kepala();
cek_user();



/*Ambil Perintah*/
$cari=$_GET['pencarian'];
$where=$_GET['where'];
$kd_buku=$_GET['kmb'];
if($where==''){
    $where='siswa';
}

$kmbl=$_GET['kembali'];


$kembaline=$_GET['kembali'];

if($kmbl==''){
    $kmbl='0';
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
$jajal="$t $b $th";
//End tglblnthn

$dari=mysql_query("select * from pinjaman");
$dari=mysql_num_rows($dari);
$belum=mysql_query("select * from pinjaman where kembali='0'");
$belum=mysql_num_rows($belum);


/*Lunasi dendo*/
if($_GET['denda']=='lunas'){
    $hapus=mysql_query("update siswa set denda='0' where no_induk='$cari'");
    
    if($val!='0'){
        $val=$_GET['tot'];
        $skg=sekarang();
        $kas=new db();
        $sal_akhr=$kas->single("select saldo from kas order by id DESC limit 0,1");
        $saldo=$sal_akhr+$val;
        $convert=get_nama($cari);
        mysql_query("insert into kas set masuk = '$val', tgl = '$skg', ket='Denda dari $convert', saldo='$saldo'"); 
    }
        if($hapus){
            catat($_SESSION['nama'],"Denda Lunas dari $cari");
            echo "<script type='text/javascript'>alert('Denda Lunasss !!')</script>";
            echo "<script type='text/javascript'>window.location='peminjaman.php?pencarian=".$cari."'</script>";
        }
        
}elseif($_GET['denda']=='batal'){
    $hapus=mysql_query("update siswa set denda='0' where no_induk='$cari'");
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
        mysql_query("update siswa set denda ='$value' where no_induk='$cari'");
        catat($_SESSION['nama'],"Set Denda $cari -> $value");
    }
}

/*Kembali Semua*/
$tgl_kembali=sekarang();
if(isset($_POST['semua'])){
    $kmb=mysql_query("update pinjaman set kembali='1', kembaline='$tgl_kembali' where siswa='$cari' && kembali='0'");
    $buk=mysql_query("update buku set status='Ada', peminjam='0' where peminjam='$cari'");
    //$tempo=
    if($kmb && $buk){
        $c=mysql_query("delete from tempo where induk ='$cari'");
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
    $kmb=mysql_query("update pinjaman set kembali='1', kembaline='$tgl_kembali' where siswa='$cari' && buku='$kd_buku' && tgl_pinjam='$tgl_kmbl'");
    $buk=mysql_query("update buku set status='Ada', peminjam='0' where peminjam='$cari' && kd_buku='$kd_buku'");
    //$tempo=
    if($kmb && $buk){
        $s=mysql_query("delete from tempo where buku='$kd_buku'");
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
    $pem=mysql_query("select * from siswa where no_induk = $cari");
    $si=@mysql_fetch_array($pem);
    $hisi=@mysql_num_rows($pem);
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
$sql=mysql_query("select * from pinjaman where $where like '%$cari%' && kembali like '$kmbl'");
$semua=mysql_query("select * from pinjaman");
$semua=mysql_num_rows($semua);
$jumlah=mysql_num_rows($sql);
$jhal=ceil($jumlah/$bts);


/*Ambil Data Peminjaman*/

if($_GET['tanggal']=='' && $_GET['bulan']=='' && $_GET['tahun']==''){
    $jajal='';
}elseif($_GET['tanggal']==''){
    $jajal="$b $th";
}elseif($_GET['bulan']==''){
    $jajal="$t ".sekarang(bln)." $th";
}

$sql=mysql_query("select * from pinjaman where $where like '%$cari%' && kembali like '$kmbl' && tgl_pinjam like '%$jajal%' order by siswa ASC limit $mulai, $bts");
$ht=mysql_num_rows($sql);
//
$sqll=mysql_query("select * from pinjaman where $where like '%$cari%' && kembali like '$kmbl' && tgl_pinjam like '%$jajal%' order by siswa");
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
                    <option value='0' <?php if($_GET['kembali']=='0'){ echo "selected='selected'";} ?>>Belum Kembali</option>
                    <option value='1' <?php if($_GET['kembali']=='1'){ echo "selected='selected'";} ?>>Kembali</option>
                    <option value='all' <?php if($_GET['kembali']=='all'){ echo "selected='selected'";} ?>>Semua</option> 
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
                    <option <?php if($_GET['bulan']=='Januari'){ echo "selected='selected'";} ?>>Januari</option>
                    <option <?php if($_GET['bulan']=='Pebruari'){ echo "selected='selected'";} ?>>Pebruari</option>
                    <option <?php if($_GET['bulan']=='Maret'){ echo "selected='selected'";} ?>>Maret</option>
                    <option <?php if($_GET['bulan']=='April'){ echo "selected='selected'";} ?>>April</option>
                    <option <?php if($_GET['bulan']=='Mei'){ echo "selected='selected'";} ?>>Mei</option>
                    <option <?php if($_GET['bulan']=='Juni'){ echo "selected='selected'";} ?>>Juni</option>
                    <option <?php if($_GET['bulan']=='Juli'){ echo "selected='selected'";} ?>>Juli</option>
                    <option <?php if($_GET['bulan']=='Agustus'){ echo "selected='selected'";} ?>>Agustus</option>
                    <option <?php if($_GET['bulan']=='September'){ echo "selected='selected'";} ?>>September</option>
                    <option <?php if($_GET['bulan']=='Oktober'){ echo "selected='selected'";} ?>>Oktober</option>
                    <option <?php if($_GET['bulan']=='Nopember'){ echo "selected='selected'";} ?>>Nopember</option>
                    <option <?php if($_GET['bulan']=='Desember'){ echo "selected='selected'";} ?>>Desember</option>
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
    
    <?php if($hisi=='1' && $_GET['kembali']=="0" && $ht!='0'){ //jika siswa di temukan 1?>
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
    <tr align='center'>
        <td width='30'>No.</td>
        <td width='50'>Peminjam</td>
        <td>Kode</td>
        <td>Judul</td>
        <td>Tgl. Pinjam</td>
        <td>Jatuh Tempo</td>
        <td>Tgl. Kembali</td>
        <td>Petugas</td> 
    </tr>
    </thead>
    <?php $i=1; while($p=mysql_fetch_array($sql)){?>                
        <tr>
            <td><?php echo $i; ?></td>
            <td><a class="list" href='#' onclick='window.location="?pencarian=<?php echo $p['siswa']; ?>&where=siswa&kembali=0<?php //echo $kembaline; ?>"' title='Klik Untuk Detail'><?php echo $p['siswa']; ?></a></td>
            <td><a class="list" onclick='window.location="?pencarian=<?php echo $p['siswa']; ?>&where=siswa&kembali=0<?php //echo $kembaline; ?>"' href="#"><?php echo $p['buku']; ?></a></td>
            <td><a class="list" onclick='window.location="?pencarian=<?php echo $p['siswa']; ?>&where=siswa&kembali=0<?php //echo $kembaline; ?>"' href="#"><?php echo $p['judul']; ?></a></td>
            <td><?php echo $p['tgl_pinjam']; ?></td>
            <td><b title="Klik Tanggal Jatuh Tempo Untuk Menghitung Denda" onclick='getElementById("tempo").value="<?php echo $p['tgl_kembali']; ?>";'><?php echo $p['tgl_kembali']; ?></b></td>
            <td><?php echo $p['kembaline'];?></td>
            <td><?php echo $p['petugas'];?></td>
                   
        </tr>
    <?php $i++; } ?>
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
