<?php
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

?>
<html>
<head>
<title>Login | LIBSKA - SkanSoft&trade;</title>
<link href="./tampilan/bootstrap/css/bootstrap.min.css" rel="stylesheet">


<script src='./tampilan/jq.js' type='text/javascript'></script>
<script type='text/javascript'>
    $(document).ready(function(){
        $('#user').focus();
    })
</script>
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
<style>
.login {
    width: 240px;
    margin: 10% auto;
    background-color: white;
    border: 1px solid #999;
    border: 1px solid rgba(0, 0, 0, 0.3);
    -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
    outline: 0;
    -webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
    box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding-box;
    background-clip: padding-box;
}
</style>
</head>    
<body>
<?php
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
        $script= "<script type='text/javascript'>";
        $script.="$('document').ready(function(){";
        $script.="$('#result').html('<p class=\'alert alert-error\'>Username dan Password tidak cocok !</p>');";
        $script.="})";
        $script.="</script>";
        echo $script;
    }
};
?>
<div class="login" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: block;">
                <div class="modal-header">
                    <h3 id="myModalLabel">Login User</h3>
                </div>
                <form action='' method='POST'>
                <div class="modal-body">
                    <table>
                    <tbody><tr>
                            <td width="100%" id="tdUser">
                                <input id="user" type="text" name="nama" style="height:40px; width: 100%;" placeholder="User ID" value="">
                            </td>
                    </tr>
                    <tr>
                            <td id="tdPass">
                                <input type="password" id="pass" name="kunci" style="height:40px" placeholder="Password" value="">
                            </td>
                    </tr>
                    </tbody>
                    </table>
                <div id="message"><!-- Result Message Here --></div>
		</div>
		<div id="result" style="margin: 5px;"><!-- Result --></div>
                <div class="modal-footer" style="text-align: center;">
                   <input class='submit btn btn-info span2' type='submit' value='Masuk' name='tmbl'>
                </div>
                </form>
</div>    
</body>    
</html>
