<?php
/*
File: kepala.php
Fungsi: Mengatur Tampilan bagian atas
Auth: ShowCheap
*/
//error_reporting(0);
cek_user();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd"
    >
<html lang="en">
<head>
    <title>Perpustakaan <?php echo get_sistem("nama"); ?> | LIBSKA <?php echo getVersion(); ?></title>
    <link rel='stylesheet' href='./tampilan/gaya.css'>
    <link href="./tampilan/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src='./tampilan/jq.js' type='text/javascript'></script>
    <script src="./tampilan/bootstrap/js/bootstrap.min.js"></script>
    <script src='./tampilan/ceklip.js' type='text/javascript'></script>
    <script>
    function setFocus() {
      var loginForm = document.getElementById("cari");
      if (loginForm) {
        loginForm["pencarian"].focus();
      }
    }
  </script>
</head>
<body onload='setFocus()'>
<!--modal popup pengaturan user-->
<div class="modal hide fade" id="pustakawan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top: -290px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
    <h3>Petugas Pustakawan</h3>
  </div>
  <div class="modal-body" style="max-height: 500px;">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    
    <p>Memuat Data..</p>
    
  </div>
  <div class="modal-footer">
  </div>
  
</div>
<script type="text/javascript">
  $('#pustakawan').on('hidden', function() {
	$(this).removeData('modal');
      });
</script>
<!--end modal-->
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <a class="brand" href="#" style="margin-left: 5px;"> Libska V3 </a>
    <ul class="nav">
        <li class="divider-vertical"></li>
        <li><a rel='tooltip' data-placement='bottom' data-original-title='Tooltip on bottom' href='index.php' ><i class=" icon-home"></i></a></li>
        <li class="divider-vertical"></li>
        <li><a href='#' onclick='window.location="buku.php"' title='Dafar Buku'><i class=" icon-book"></i> <b>Katalog Buku</b></a></li>
        <li class="divider-vertical"></li>
        <li><a href='#' onclick='window.location="peminjaman.php"' title='Daftar Pinjaman'><i class=" icon-folder-open"></i> <b>Peminjaman</b></a></li>
        <li class="divider-vertical"></li>
        <li class="dropdown">
            <a href='#' class="dropdown-toggle" role="button" data-toggle="dropdown" data-target="#" title='Daftar Pinjaman'><i class="  icon-th-list"></i> <b>Menu</b> <b class="caret"></b></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li><a href='#' onclick='window.location="index.php?stat"' title='Lihat Statistik Dan Unduh'><i class="icon-tasks"></i> Statistik</a></li>
                <li><a href='#' onclick='window.location="log.php"' title='Lihat Catatan Log / Aktivitas Pengguna'><i class="icon-list-alt"></i> Aktivitas</a></li>
                <li><a href='#' onclick='window.location="kas.php"' title='Lihat Catatan Kas'><i class="icon-shopping-cart"></i> Catatan Kas</a></li>
                <li><a href='#' onclick='window.location="anggota.php"' title='Lihat Data Siswa'><i class="icon-user"></i> Anggota</a></li>
                <li><a href='#' onclick='window.location="backup.php"' title='Backup Restore Database'><i class="icon-hdd"></i> Database</a></li>
                <li><a href='#' onclick='window.location="atur.php"' title='Pengaturan'><i class=" icon-wrench"></i> Pengaturan</a></li>
            </ul>
        </li>        
        <li class="divider-vertical"></li>      
    </ul>
    <form class="navbar-form pull-left" action="buku.php" method="get">
        <input type="text" class="span2" name="pencarian" placeholder="Cari kode buku" >
        <button type="submit" class="btn">Cari</button>
    </form>
<ul class="nav pull-right">
                    <li style="padding: 10px; font-weight: bold;"><?php echo sekarang(); ?></li>
                    <li class="divider-vertical"></li>
		    <li class="dropdown">
			<a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
			    <i class='icon-user'></i> <?php echo $_SESSION['nama']; ?>
			    <b class="caret"></b>
			  </a>
			  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
			    <li><a href="keluar.php" title='Keluar Aplikasi' >Keluar</a></li>
			    <li><a tabindex="-1" data-toggle="modal" data-target="#pustakawan" href="ajax/ajax-pustakawan.php?mode=ubah&uid=<?php echo $_SESSION['uid']; ?>">Ganti Password</a></li>
			  </ul>
		    </li>
		    
		</ul>
  </div>
</div>
<div id='badan'>
