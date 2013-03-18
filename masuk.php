<?php
error_reporting(0); 
session_start();
/*
File: masuk.php
Fungsi: Form untuk login.
Auth: ShowCheap
*/
if(file_exists("upgrade/index.php")){
    header('location: upgrade/index.php');
    exit(0);
}
require 'sistem/config.php';
$pencet=$_POST['tmbl'];
sambung();
if(isset($_SESSION['level'])){
   $sesi=$_SESSION['level'];    
    if(($sesi=='Admin' || $sesi=='Pustakawan')){
        header('location: index.php');
    }
}
$nama=mysql_real_escape_string($_POST['nama']);
$kunci=md5($_POST['kunci']);
if ($pencet != '' && $nama != '' && $kunci != ''){
    $s=mysql_query("SELECT * FROM tbl_pustakawan WHERE user='".$nama."' AND kunci='".$kunci."'");
    $c=mysql_num_rows($s);
    if($c == '1'){
      $t= mysql_fetch_array($s);
      $log=$t['login'];
      $log=$log+1;
      mysql_query("UPDATE tbl_pustakawan SET login='$log' WHERE user='$nama'");
      $_SESSION['nama']=$t['nama'];
      $_SESSION['level']=$t['level'];
      $_SESSION['uid']=$t['id'];
      catat($_SESSION['nama'],"Berhasil Login");
      //header('location: index.php');
      echo "<script>window.location='index.php'</script>";
      exit();
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
        alert("Libska Version <?php echo getVersion(); ?>\nPerpustakaan <?php echo get_sistem("nama"); ?>\n\nProgram By: Sucipto\nDedicated To: SMKN 1 Ngawi\nVisit: www.sucipto.net\n\nCopyright 2012 Alright Reserved")
    }
</script>
<script src='./tampilan/jq.js' type='text/javascript'></script>
<script type='text/javascript'>
    $(document).ready(function(){
       
            $.ajax({
                url:'run.php',
                success: function(data){
                    //$("#res").html(data);
                    
                }
            })
       
      });    
</script>
</head>    
<body>
<div id='login' style="width: 300px; border: 1px solid; margin: 100px auto; padding: 10px;">

        <fieldset>
            <legend> <img src='tampilan/gambar/pinjem.png'> Login Petugas Perpustakaan</legend>
            <form action='' method='POST'>
        <table border='0' cellpadding='3' cellspacing='2' width='100%' style="margin-left:50px;">
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
<i style='font-size: 12px; font-style: italic; color: gray;'> Copyright &copy; <?php echo date('Y'); ?> | <b style='font-size: 11px;'>Libska Version: <?php echo getVersion();; ?></b> | <b onclick='about()' style='font-size: 12px;'>Tentang</b></i>
</div>
</body>    
</html>
