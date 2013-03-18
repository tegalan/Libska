<?php
require '../sistem/config.php';
sambung();
$induk=mysql_real_escape_string($_POST['siswo']);
$siswa=new db();
$siswa->sql("SELECT * FROM tbl_anggota WHERE no_induk = '$induk'");
$siswa->hasil();
?>
<table cellpadding='1' cellspacing='3' id="tabel_ajax">
    <tr>
        <td>Nama</td><td>:</td><td><?php echo $siswa->hasil['nama']; ?></td>
    </tr>
    <tr>
        <td>No Induk</td><td>:</td><td><?php echo $siswa->hasil['kelas']; ?> <?php echo $siswa->hasil['jurusan']; ?></td>
    </tr>
    <tr>
        <td>Meminjam</td><td>:</td><td><?php echo $meminjam ?></td>
    </tr>
</table>