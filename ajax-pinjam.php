<?php
session_start();
/*
File: ajax-pinjam.php
Fungsi: Peminjaman dengan form ajax
Auth: ShowCheap
*/
require 'sistem/config.php';
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
    $waktu = date("d-n-Y", mktime(0,0,0,date("m"),date("d")+14,date("Y")));
    $x=explode('-', $waktu);
    $hari2=$x['0']; 
    $bulan2=$x['1'];
    $tahun2=date('Y');
    $bln2=$indo[$bulan2-1];
    //**waktu kembali yg sudah fix**//
    $kembali="$hari2 $bln2 $tahun2";
?>

<script type='text/javascript'>
    $(document).ready(function(){
        $("#tutup").click(function(){
            $("#ajx-pinjam").hide()
            window.location.reload(true);
            //$(".frmPinjem").toggle();
        })
    })
</script>
<script type='text/javascript'>
    $(document).ready(function(){
        $("#tmblPinjam").focus();
        $("#ajx-lakukan").submit(function(){
            $.ajax({
                url:'do-pinjam.php',
                type: 'POST',
                data: $(this).serialize(),
                success:function(data){
                    $("#inner").html(data);
                }
            })
        })
    });
</script>
<script type="text/javascript">
    $("#frm-siswa").submit(function(){
            $.ajax({
                url:'ajax/ajax-siswa.php',
                type: 'POST',
                data: $(this).serialize(),
                success:function(data){
                    $("#tabel_ajax").html(data);
                }
            })
        })
</script>
<script type="text/javascript">
    function cari(){
            $("#frm-siswa").submit(function(){
            $.ajax({
                url:'ajax-siswa.php',
                type: 'POST',
                data: $(this).serialize(),
                success:function(data){
                    $("#tabel_ajax").html(data);
                }
            })
         })
            //alert('a');
        }
</script>
<script type='text/javascript'>
    function foc() {
      var loginForm = document.getElementById("ajx-lakukan");
      if (loginForm) {
        loginForm["tombul"].focus();
      }
    }
</script>
<?php
$induk=mysql_real_escape_string($_GET['siswa']);
$siswa=new db();
$siswa->sql("select * from siswa where no_induk = '$induk'");
$siswa->hasil();

?>
<div id='background'> 

<div id='ajx-pinjam'>
<div id='inner'>
<?php if($siswa->getJml()!='0'){
$jml=new db();
$meminjam=$jml->baris("select * from buku where peminjam='$induk' && status='Kosong'");
?>
<h4>Data Peminjam</h4>
<?php if($meminjam > 6){
echo "
        <div class=\"alert alert-error fade in\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>
            Siswa Sudah Meminjam lebih dari 6 buku.
          </div>";
}
?>
<form id='frm-siswa' onsubmit='return false' name="form-siswa">
<table cellpadding='1' cellspacing='3'>
    <tr>
        <td>No Induk</td><td width="20px">:</td>
        <td><input type="text" name="siswo" placeholder="No Induk" style="height: 20px;" onkeyup="cari()">
        <input type="submit" value="ok"></td>
    </tr>
</table>
</form>
<div id="tabel_ajax">
    
</div>
<?php /*Buku*/
$kbuk=mysql_real_escape_string($_GET['kode']);
//echo $kbuk;
$buku=new db();
$buku->sql("select * from buku where kd_buku='$kbuk'");
$buku->hasil();
$kosong=new db();
$jmlu=$kosong->baris("select * from buku where kd_buku='$kbuk' && status!='Kosong'");

?>

<h4>Data Buku</h4>
    <table cellpadding='1'>
        <tr>
            <td>Kode Buku</td><td width="20px">:</td><td><?php echo $buku->hasil['kd_buku']; ?></td>
        </tr>
        <tr>
            <td>Judul Buku</td><td>:</td><td><?php echo $buku->hasil['judul']; ?></td>
        </tr>
    </table><br>
Periode:
<?php echo "<b>".sekarang()."</b> Sampai <b>".$kembali."</b>"; ?>
<?php }else{
    echo "Data Tidak Ditemukan<br>";
}?>
<?php
if($jmlu=='1'){ ?>
<form id='ajx-lakukan' onsubmit='return false' style="margin-top: 5px;">
    <input type='hidden' name='kd_buku' value='<?php echo $buku->hasil['kd_buku']; ?>'>
    <input type='hidden' name='siswa' value='<?php echo $siswa->hasil['no_induk']; ?>'>
    <input type='hidden' name='kembali' value='<?php echo $kembali; ?>'>
    <input class='btn btn-primary' type='submit' name='tombul' id='tmblPinjam' value='Pinjam'>
    <a href="#" class="btn btn-warning" data-dismiss="modal">Batal</a>
</form>
<? }else{
 echo "<b>Sudah Dipinjam</b><br>";   
}?>

</div>
</div>
</div><!--background-->   