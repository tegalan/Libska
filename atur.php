<?php
session_start();
/*
File: atur.php
Fungsi: pengaturan
Auth: ShowCheap
*/
include 'sistem/config.php';
//include 'run.php';
sambung();
get_kepala();
if($_POST['atur']!=''){
    $nama=mysql_real_escape_string($_POST['nama']);
    $alamat=mysql_real_escape_string($_POST['alamat']);
    $web=mysql_real_escape_string($_POST['web']);
    //echo "$nama $alamat $web";
    set_sistem("Nama",$nama);
    set_sistem("Alamat",$alamat);
    set_sistem("web",$web);
}
?>
<fieldset>
<legend>Pengaturan</legend>
<b>Sistem</b><br>
<form action='' method='post'>
    <table>
        <tr>
            <td>Nama Perpustakaan</td><td>:</td><td><input size='30' type='text' name='nama' value='<?php echo get_sistem("nama"); ?>'></td>
        </tr>
        <tr>
            <td>Alamat Perpustakaan</td><td>:</td><td><input size='30' type='text' name='alamat' value='<?php echo get_sistem("alamat"); ?>'></td>
        </tr>
        <tr>
            <td>Website Perpustakaan</td><td>:</td><td><input size='30' type='text' name='web' value='<?php echo get_sistem("web"); ?>'></td>
        </tr>
        <tr>
            <td>Versi Libska</td><td>:</td><td><?php echo get_sistem("versi"); ?></td>
        </tr>
        <tr>
            <td><input type='submit' value='Simpan' name='atur'></td><td></td><td><input type='reset' value='Reset'></td>
        </tr>
    </table>
</form>
<hr>
<b>Pengguna</b><br>

<table border='1' cellspacing='0'>
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
?>
</table>

</fieldset>

<?
get_kaki();
?>