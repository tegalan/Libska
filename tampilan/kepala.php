<?php
/*
File: kepala.php
Fungsi: Mengatur Tampilan bagian atas
Auth: ShowCheap
*/
cek_user();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd"
    >
<html lang="en">
<head>
    <title>Perpustakaan <?php echo get_sistem("nama"); ?> | LIBSKA <?php echo get_sistem("versi"); ?></title>
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

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <a class="brand" href="#" style="margin-left: 5px;"> Libska V3 </a>
    <ul class="nav">
        <li class="divider-vertical"></li>
        <li><a rel='tooltip' data-placement='bottom' data-original-title='Tooltip on bottom' href='index.php' ><img src='tampilan/gambar/omah.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>
        <li><a href='#' onclick='window.location="buku.php"' title='Dafar Buku'><img src='tampilan/gambar/buku.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>
        <li><a href='#' onclick='window.location="peminjaman.php"' title='Daftar Pinjaman'><img src='tampilan/gambar/kartu.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>        
        <li><a href='#' onclick='window.location="index.php?stat"' title='Lihat Statistik Dan Unduh'><img src='tampilan/gambar/donlot.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>        
        <li><a href='#' onclick='window.location="log.php"' title='Lihat Catatan Log / Aktivitas Pengguna'><img src='tampilan/gambar/log.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>        
        <li><a href='#' onclick='window.location="kas.php"' title='Lihat Catatan Kas'><img src='tampilan/gambar/cal.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>        
        <li><a href='#' onclick='window.location="siswa.php"' title='Lihat Data Siswa'><img src='tampilan/gambar/wong.png' width='22' height='22'></a></li>
        <li class="divider-vertical"></li>        
        <?php if($_SESSION['level']=='Admin'){ echo "<li><a href='#' onclick='window.location=\"buku.php?tambah=1\"' title='Tambah Buku'><img src='tampilan/gambar/tambah.png' width='22' height='22'></a></li>"; } ?>
         <li class="divider-vertical"></li>          
        <li><a href='#' onclick='window.location="atur.php"' title='Pengaturan'><img src='tampilan/gambar/gir.png' width='22' height='22'></a></li>
         <li class="divider-vertical"></li>  
    </ul>
    <form class="navbar-form pull-left">
  <input type="text" class="span2">
  <button type="submit" class="btn">Cari</button>
</form>
<ul class="nav pull-right">
                    <li style="padding: 10px; font-weight: bold;"><?php echo sekarang(); ?></li>
                    <li class="divider-vertical"></li>
		    <li class="dropdown">
			<a class="dropdown-toggle" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
			    <?php echo $_SESSION['nama']; ?>
			    <b class="caret"></b>
			  </a>
			  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
			    <li><a href="keluar.php" title='Keluar Aplikasi' >Keluar</a></li>
			    <li><a tabindex="-1" href="#">Ganti Password</a></li>
			  </ul>
		    </li>
		    
		</ul>
  </div>
</div>
<div id='badan'>
