<?php
error_reporting(0);
session_start();
/*
File    : ajax-pinjam.php
Fungsi  : Peminjaman dengan form ajax
Auth    : ShowCheap
Fixed by: Asong Soekamti
*/
require '../sistem/config.php';
require '../sistem/siswa.php';
require '../sistem/class_buku.php';
cek_user();
sambung();
//echo "Maaf fitur ini belum jadi, hehe";
//exit();
//menampilkan daftar
if($_GET['mode']=='list'){
?>
    <table border='0' cellspacing='0' class="table">
    <tr>
        <td>No.</td><td>User</td><td>Nama</td><td>Level</td><td>Login</td>
    </tr>
    <?php
    $ser=mysql_query("select * from anggota");
    $n=1;
    while($us=mysql_fetch_array($ser)){
        echo "<tr>";
        echo "<td>$n</td>";
        echo "<td>".$us['user']."</td>";
        echo "<td>".$us['nama']."</td>";
        echo "<td>".$us['level']."</td>";
        echo "<td>".$us['login']."</td>";
        echo "</tr>";
        $n++;
    }
    echo "</table>";
}elseif($_GET['mode']=='ubah'){
   //jika modus edit
   $id=$_SESSION['uid'];
   $plama=$_POST['pwdlama'];
   $pbaru=mysql_real_escape_string($_POST['wpdbaru']);
   $pulang=mysql_real_escape_string($_POST['repeat']);
   $select=  mysql_query("SELECT * from tbl_pustakawan WHERE id=$id");
   $data= mysql_fetch_array($select);
   $passwordlama=$data['kunci'];
   if($_POST['ngirim']=='ya'){
       if($passwordlama==md5($plama)){
            if($pbaru==$pulang){
                $kunci=md5($pbaru);
                $suksess = mysql_query("UPDATE tbl_pustakawan SET kunci='$kunci' WHERE id='$id'");
                if($suksess){
                    echo "<i class='alert alert-success'>Password berhasil di ganti !</i>";
                    exit();
                }else{
                    echo "<i class='alert alert-error'>Password Gagal di ganti !</i>";
                    exit();
                }//endquery
            }//end cocok
            else{
                echo "<i class='alert alert-error'>Ulangi password tidak sesuai !</i>";
                    exit();
            }
       }
       else{
            echo "<i class='alert alert-error'>"."Password lama salah!!". "</i>";
            exit();
       }
   }//endsubmit
   ?>
   <script type="text/javascript">
    $("#ubahpwd").submit(function(){
            $.ajax({
                url:'ajax/ajax-pustakawan.php?mode=ubah&do=yes',
                type: 'POST',
                data: $(this).serialize(),
                success:function(data){
                    $("#content").html(data);
                    //alert('berhasil');
                }
            })
        })
    </script>
    <div id="content"></div>
   <form id="ubahpwd" onsubmit="return false" action="<?php $_SERVER['PHP_SELF']; ?>">
   <input type="hidden" name="ngirim" value="ya">
    <table>
        <tr>
            <td><input type="password" placeholder="Password lama" name="pwdlama"></td>
            
        </tr>
        <tr>
            <td><input type="password" placeholder="Password Baru" name="wpdbaru"></td>
        </tr>
        <tr>
            <td><input type="password" placeholder="Ulangi Password Baru" name="repeat"></td>
        </tr>
    	<tr><td><input name="submit" type="submit" value="Ganti" class="btn btn-success" style="width: 100%;"></td></tr>
		
    </table>
   </form>
   <?php
}elseif($_GET['mode']=='tambah'){
    //mode tambah
}else{
    echo "Fungsi Tidak di temukan";
}
?>

