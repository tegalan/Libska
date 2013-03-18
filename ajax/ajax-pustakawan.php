<?php
session_start();
/*
File: ajax-pinjam.php
Fungsi: Peminjaman dengan form ajax
Auth: ShowCheap
*/
require '../sistem/config.php';
require '../sistem/siswa.php';
require '../sistem/class_buku.php';
cek_user();
sambung();
echo "Maaf fitur ini belum jadi, hehe";
exit();
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
   $id=$_GET['uid'];
   $plama=$_POST['pwdlama'];
   $pbaru=mysql_real_escape_string($_POST['wpdbaru']);
   $pulang=mysql_real_escape_string($_POST['repeat']);
   if($_POST['ngirim']=='ya'){
    if($pbaru==$pulang){
     $kunci=md5($pbaru);
     if(mysql_query("UPDATE anggota SET kunci='$kunci' WHERE id='$id'")){
         echo "<i class='alert alert-success'>Password berhasil di ganti !</i>";
         exit();
     }else{
         echo "<i class='alert alert-error'>Password Gagal di ganti !</i>";
         exit();
     }//endquery
    }//end cocok
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
   <form id="ubahpwd" onsubmit="return false">
   <input type="hidden" name="ngirim" value="ya">
    <table>
        <tr>
            <td><input type="password" placeholder="Password lama" name="pwdlama"></td>
            <td rowspan="3"><input name="submit" type="submit" value="Ganti" class="btn btn-success" style="width: 100%"></td>
        </tr>
        <tr>
            <td><input type="password" placeholder="Password Baru" name="wpdbaru"></td>
        </tr>
        <tr>
            <td><input type="password" placeholder="Ulangi Password Bama" name="repeat"></td>
        </tr>
    </table>
   </form>
   <?php
}elseif($_GET['mode']=='tambah'){
    //mode tambah
}else{
    echo "Fungsi Tidak di temukan";
}
?>

