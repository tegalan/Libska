<?php
session_start();
/*
File: masuk.php
Fungsi: Form untuk login.
Auth: ShowCheap
*/
require 'sistem/config.php';
$pencet=$_POST['tmbl'];
sambung();
if(isset($_SESSION['nama'])){
    header('location: index.php');
}
$nama=mysql_real_escape_string($_POST['nama']);
$kunci=md5($_POST['kunci']);
if ($pencet != '' && $nama != '' && $kunci != ''){
    $s=mysql_query("select * from anggota where user='".$nama."' and kunci='".$kunci."'");
    $c=mysql_num_rows($s);
    if($c == '1'){
      $t= mysql_fetch_array($s);
      $log=$t['login'];
      $log=$log+1;
      mysql_query("update anggota set login='$log' where user='$nama'");
      $_SESSION['nama']=$t['nama'];
      $_SESSION['level']=$t['level'];
      catat($_SESSION['nama'],"Berhasil Login");
      header('location: index.php');
    }else{
        catat($nama,"Gagal Login");
        echo "<script type='text/javascript'>alert('Kombinasi Salah !');</script>";
    }
};
?>
<html>
<head>
<title>Login | LIBSKA - SkanSoft&trade;</title>
<link href="./tampilan/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<script type='text/javascript'>
    function about(){
        alert("Libska Version <?php echo get_sistem("versi"); ?>\nPerpustakaan <?php echo get_sistem("nama"); ?>\n\nProgram By: Sucipto\nDedicated To: SMKN 1 Ngawi\nVisit: www.sucipto.net\n\nCopyright 2012 Alright Reserved")
    }
</script>

</head>    
<body>
<div id='login' style="width: 500px; border: 1px solid; margin: 100px auto; padding: 10px;">
<center><h2><i>Perpustakaan <?php echo get_sistem("nama"); ?></i></h2></center>
        <fieldset>
            <legend> <img src='tampilan/gambar/pinjem.png'> Login Petugas Perpustakaan</legend>
            <form action='' method='POST'>
        <table border='0' cellpadding='3' cellspacing='2' width='100%'>
            <tr>
                <td><input type='text' placeholder="Username" name='nama' style="height: 30px;"></td>
            </tr>
           
            <tr>
                <td><input type='password' placeholder="Kunci" name='kunci' style="height: 30px;"></td>
            </tr>
            
            <tr>
                <td valign='top' colspan='3'><input class='submit btn' type='submit' value='Masuk' name='tmbl'></td>
            </tr>
        </table>
        </form>
        </fieldset>
<i style='font-size: 12px; font-style: italic; color: gray;'> Copyright &copy; <?php echo date('Y'); ?> | SkanSoft &trade; <b style='font-size: 11px;'>LibSka Version: <?php echo get_sistem("versi"); ?></b> | <b onclick='about()' style='font-size: 12px;'>Tentang</b></i>
</div>
</body>    
</html>
