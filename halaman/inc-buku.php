<?php
/*
File: inc-buku.php
Fungsi: File untuk include halaman buku.
Auth: ShowCheap
*/
//cek_user();

$key=mysql_real_escape_string($_GET['pencarian']);
$where=mysql_real_escape_string($_GET['where']);
$order=mysql_real_escape_string($_GET['order']);
$stat=mysql_real_escape_string($_GET['status']);
$bates=$_GET['banyak'];
putus();
if($where==''){
  $where='kd_buku';  
}
if($order==''){
    $order='kd_buku';
}
if($stat=='All'){
    $stat='%%';
}
if($stat==''){
    $stat='1';
}
if($bates==''){
    $bates='100';
}

//**Page**//
$bts= $bates;
$hal = $_GET['hal'];
if (!isset($hal)){
    $mulai=0;
}else{
    $mulai= $hal * $bts;
};
$a=mysql_query("SELECT * FROM tbl_buku WHERE $where LIKE '%$key%' && status LIKE '$stat' ORDER BY $order ASC");
//Debug Program
//echo "SELECT * FROM tbl_buku WHERE $where LIKE '%$key%' && status LIKE '$stat' ORDER BY $order ASC";
$semua=mysql_query("SELECT * FROM tbl_buku");
$semua=mysql_num_rows($semua);
$jumlah=mysql_num_rows($a);
$jhal=ceil($jumlah/$bts);

?>

<script type='text/javascript'>
  function kirim(){
    document.forms['frm-buku'].submit();
  }
</script>
<!--modal popup peminjaman-->
<div class="modal hide fade" id="pop-pinjam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top: -290px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
    <h3>Peminjaman</h3>
  </div>
  <div class="modal-body" style="max-height: 500px;">
    
    <img src="../tampilan/gambar/lodeng2.gif">
    
  </div>
  <div class="modal-footer">
    
  </div>
  
</div>
<!--modal popup tidak bisa di pinjam-->
<div class="modal hide fade" id="pop-nopinjam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
    <h3>Peminjaman</h3>
  </div>
  <div class="modal-body">
    
    <p class="alert alert-success">Buku yang anda maksud sudah di pinjam</p>
    
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
  </div>
  
</div>
<script type="text/javascript">
  $('#pop-pinjam').on('hidden', function() {
	$(this).removeData('modal');
      });
</script>
<h2>Katalog Buku <a href="buku.php?tambah=1" class="btn btn-info"><i class=' icon-plus icon-white'> </i> Tambah</a> <a href="import/index.php?target=buku" class="btn btn-info"><i class=' icon-download-alt icon-white'> </i> Import Data</a></h2>
<form id='cari' action='' method='GET' name='frm-buku'>
<table style='background-color: #99ccff;' width='100%' cellpadding='3' cellspacing='1' class="table table-striped table-hover">
    <tr>
	<td><input autocomplete='off' onchange='kirim()' class='cari' type='text' name='pencarian' value="<?php echo stripcslashes($key); ?>" size='15'></td>
	<td>
	    <select name='where' onchange='kirim()'>
		<option value='kd_buku'>--Cari Berdasar--</option>
		<option value='kd_buku' <?php if($_GET['where']=='kd_buku'){ echo "selected='selected'";} ?>>Kode Buku</option>
		<option value='judul' <?php if($_GET['where']=='judul'){ echo "selected='selected'";} ?>>Judul Buku</option>
		<option value='pengarang' <?php if($_GET['where']=='pengarang'){ echo "selected='selected'";} ?>>Pengarang</option>
		<!--<option value='thn_terbit' <?php //if($_GET['where']=='thn_terbit'){ echo "selected='selected'";} ?>>Tahun Terbit</option>-->
		<option value='penerbit' <?php if($_GET['where']=='penerbit'){ echo "selected='selected'";} ?>>Penerbit</option>
	    </select>
	</td>
	
	</td>
	<td>
	    <select name='order' onchange='kirim()' style="width: 150px;">
		<option value='kd_buku'>--Urutkan--</option>
		<option value='kd_buku' <?php if($_GET['order']=='kd_buku'){ echo "selected='selected'";} ?>>Kode Buku</option>
		<option value='judul' <?php if($_GET['order']=='judul'){ echo "selected='selected'";} ?>>Judul Buku</option>
		<option value='pengarang' <?php if($_GET['order']=='pengarang'){ echo "selected='selected'";} ?>>Pengarang</option>
		<!--<option value='thn_terbit' <?php //if($_GET['order']=='thn_terbit'){ echo "selected='selected'";} ?>>Tahun Terbit</option>-->
		<option value='penerbit' <?php if($_GET['order']=='penerbit'){ echo "selected='selected'";} ?>>Penerbit</option>
		<option value='no' <?php if($_GET['order']=='no'){ echo "selected='selected'";} ?>>No Urut</option>
	    </select>
	</td>
	<td>
	   
	</td>
	<td>
	    <select name='status' onchange='kirim()' style="width: 150px;">
		    <option value='1'>--Status--</option>
		    <option value='1'  <?php if($_GET['status']=='1'){ echo "selected='selected'";} ?>>Ada</option>
		    <option value='0'  <?php if($_GET['status']=='0'){ echo "selected='selected'";} ?>>Di Pinjam</option>
		    <option value='All'  <?php if($_GET['status']=='All'){ echo "selected='selected'";} ?>>Semua</option>
	    </select>
	</td>
	<td>
	    <select name='banyak' onchange='kirim()' style="width: 70px;">
		<option value='5'>--Banyak Data--</option>
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
	</td>
	<td><input type='submit' value='Cari' class='btn btn-primary'>
    </tr>
</table>
</form>
<table id='tbl_buku' cellspacing='1' cellpadding='3' width='100%' style='font-size: 12px;' class="table table-striped table-hover">
    <tr style='background-color: #FF9933;' align='center'>
        <td>No.</td>
        <td>Kode Buku</td>
        <td>Judul</td>
        <td>Pengarang</td>
        <!--<td>Tahun</td>-->
        <td>Penerbit</td>
        <td style='width: 47px;'>Menu</td>
    </tr>
    
<?php
sambung();
$a=mysql_query("SELECT * FROM tbl_buku WHERE $where LIKE '%$key%' && status LIKE '$stat' ORDER BY $order ASC LIMIT $mulai, $bts");
//Debug Program
//echo "SELECT * FROM tbl_buku WHERE $where LIKE '%$key%' && status LIKE '$stat' ORDER BY $order ASC LIMIT $mulai, $bts";
$no=1;
$chek=mysql_num_rows($a);
if($chek==1){
  ?>
  <script type="text/javascript">
    $(document).ready(function(){
        $('#tmbl-pinjam').click();
    })
    
  </script>
  <?php
}
while ($m=mysql_fetch_array($a)){
    
    if($chek=='1' && $m['status'] != 'Kosong'){
    $judulku=addslashes($m['judul']);
    //echo "<script type='text/javascript'>
    //var jawaban = confirm( 'Pinjam Buku \"".$judulku."\" ?' );
    //if ( jawaban ){
    //window.open('pinjam.php?buku=".$m['no']."','popupwindow','scrollbars=yes, width=550,height=600, resizable=0, left = 462,top = 20, toolbar=no, menubar=no');
    //}   
    //</script>";
?>
<?php
}

    if($no % 2 == 0){
		$id='genap'; 
	}else{ 
		$id='ganjil'; 
		}
echo "<tr>";
    $script="window.open('pinjam.php?buku=".$m['no']."','popupwindow','scrollbars=yes, width=550,height=600, resizable=1, left = 462,top = 20');";
    echo "<td>".$no."</td>";
    
    echo "<td width='120' >".$m['kd_buku']."</td>";
    
    echo "<td onclick='pokus".$m['no']."()' >".$m['judul']."</td>";
    
    echo "<td >".$m['pengarang']."</td>";
    //echo "<td width='20'>".$m['thn_terbit']."</td>";
    
    echo "<td >".$m['penerbit']."</td>";
    
    echo "<td>";
    
   // if($m['status']=='Ada'){
     // echo "<a href='#' title='Lihat Detail' onclick=\"".$script."\"><img src='tampilan/gambar/centang.png' width='15' height='15'></a>";
    //}else{
      //echo "<a href='#' title='Sudah Dipinjam' onclick='alert(\"Tidak tersedia untuk di pinjam !\");'><img src='tampilan/gambar/no.png' width='15' height='15'></a>";
    //}
    
    //echo admin("<a href='buku.php?tambah=1&hapus=1&buku=".$m['kd_buku']."' title='Hapus Buku' onclick='return confirm(\"Yakin Hapus Buku Ini?\");'><img src='tampilan/gambar/ping.png' width='15' height='15'></a>");
    //echo admin("<a href='buku.php?tambah=1&tBuku=".$m['kd_buku']."' title='Tambah Buku Sejenis' onclick='return confirm(\"Tambah Buku?\");'><img src='tampilan/gambar/tambah.png' width='15' height='15'></a>");
    
    ?>
                <div class="btn-group">
		    <?php if($m['status']=='1'){ ?>
                    <button id="tmbl-pinjam" class="btn btn-success tmbl-pinjam" data-toggle="modal" href="ajax/ajax-pinjam.php?buku=<?php echo $m['kd_buku'] ?>" data-target="#pop-pinjam" >Pinjam</button>
		    <?php }else{ ?>
		    <button class="btn btn-danger tmbl-pinjam" data-toggle="modal" data-target="#pop-nopinjam">Pinjam</button>
		    <?php } ?>
                    <button class="btn dropdown-toggle btn-success" data-toggle="dropdown">
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href='buku.php?tambah=1&hapus=1&buku=<?php echo $m['kd_buku'] ?>' title='Hapus Buku' onclick='return confirm("Yakin Hapus Buku Ini?");'>Hapus</a></li>
                        <li><a href='buku.php?tambah=1&tBuku=<?php echo $m["kd_buku"]; ?>' title='Tambah Buku Sejenis' onclick='return confirm("Tambah Buku?");'>Tambah</a></li>
                    </ul>
              </div>
    <?php
    echo "</td>";
echo "</tr>";
?>
<?php
$no++;
}
if($chek=='0'){
    //echo "<script type='text/javascript'>alert('Data tidak ditemukan !')</script>";
    echo "<tr><td colspan='6' align='center' class='alert alert-error'><b>Data Tidak Ditemukan Silahkan Cek Kembali</b><br><i>Mungkin Buku Sudah di Pinjam, Periksa Kembali Kata Kunci Pencarian, Atau Atur Spesifikasi (Judul Buku / Kode Buku / Pengarang)</i></tr></td>";
}
echo "<tr><td colspan='5'>Menampilkan <b>".$chek."/".$jumlah."</b> Dari <b>".$semua."</b> Buku<td></tr>";

putus();
?>    
</table>
<?php
if ('1'=='1'){ ?>
    <ul class="pager">
      <li class="previous">
	<a style='text-decoration: none;' href='<?php echo "?where=$where&pencarian=$key&banyak=$bates&hal=".max($hal-1, 0); ?>'>&larr; Sebelumnya</a>
      </li>
      <li class="next">
	<a style='text-decoration: none;' href='<?php echo "?where=$where&pencarian=$key&banyak=$bates&hal=".min($hal+1, $jhal-1); ?>'>Selanjutnya &rarr;</a>
      </li>
    </ul>  
<?php } ?>