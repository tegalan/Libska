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
    $lama=mysql_real_escape_string($_POST['lPinjam']);
    //echo "$nama $alamat $web";
    set_sistem("Nama",$nama);
    set_sistem("Alamat",$alamat);
    set_sistem("web",$web);
    set_sistem("Lama",$lama);
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
            <td>Lama Peminjaman</td><td>:</td><td><input class='input-small' type="text" name="lPinjam" value='<?php echo get_sistem("lama"); ?>'> Hari</td>
        </tr>
        <tr>
            <td>Versi Libska</td><td>:</td><td class="label label-success"><?php echo getVersion(); ?></td>
        </tr>
        <tr style="padding: 5px;">
            <td style="padding: 5px;"><input class="btn btn-info" type='submit' value='Simpan' name='atur'></td><td></td><td><input class="btn btn-danger" type='reset' value='Reset'></td>
        </tr>
    </table>
</form>
<hr>
</fieldset>

<?
get_kaki();
?>