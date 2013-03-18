<?php

@$peminjam=new db();
@$blm_kmbl=new db();
@$blm_kmbl->sql("SELECT * FROM tbl_peminjaman WHERE kembali = '0'");
@$peminjam->sql("SELECT * FROM tbl_peminjaman");


?>
<h2>Statistik</h2><br>
    
<form action='<?php echo $_SERVER['REQUEST_URI']; ?>' method='get' name='frmthn'>
&nbsp;
    <select name='thn' onchange='document.forms["frmthn"].submit()'>
        <option value='' selected>Pilih Tahun</option>
        <?php
        $sekarang=date('Y');
        $tahun=$_GET['thn'];
        for($i=2011; $i<=$sekarang; $i++){
            echo "<option value='$i' >Tahun $i</option> ";
           
        }
        $peminjam=new db();
        ?>
    </select>
    <input type='hidden' name='stat' value=''>
    <input type='submit' value='OK' class="btn btn-primary" style="margin-top:-8px">
</form>
<div style='margin: auto;'><img src='grafik/tahun.php?thn=<?php echo $_GET['thn'] ?>'></div>
<br><br>
<table border='0' cellpadding='1' cellspacing='1' width='100%'><!--Tabel Utama-->
    <tr>
        <td width='50%' valign='top' align='center'><img src='grafik/kelas.php?kelas=x'></td>
        <td width='50%' valign='top' align='center'><img src='grafik/kelas.php?kelas=xi'></td>
    </tr>
    <tr>
        <td width='50%' valign='top' colspan='2' align='center'><img src='grafik/kelas.php?kelas=xii'></td>
    </tr>
    <tr>
        <td width='50%' valign='top'>
            <table cellpadding='1' cellspacing='1' width='100%'>
                <tr>
                    <td colspan='3'><b>Siswa</b></td>
                </tr>
                <tr>
                    <td>Yang Pernah Meminjam</td>
                    <td>:</td>
                    <td><b><?php echo $peminjam->baris("SELECT * FROM tbl_anggota WHERE count != '0'"); ?> Anak</b></td>
                </tr>
                <tr>
                    <td>Tidak Pernah Meminjam</td>
                    <td>:</td>
                    <td><b><?php echo $peminjam->baris("SELECT * FROM tbl_anggota WHERE count = '0'"); ?> Anak</b></td>
                </tr>
                <tr>
                    <td>Total Siswa</td>
                    <td>:</td>
                    <td><b><?php echo $peminjam->baris("SELECT * FROM tbl_anggota"); ?> Anak</b></td>
                </tr>
                <tr>
                    <td colspan='3'><b>Buku</b></td>
                </tr>
                <tr>
                    <td>Total Peminjaman</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_peminjaman"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td>Peminjaman Kembali</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE kembali='1'"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td>Peminjaman Belum Kembali</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE kembali='0'"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td>Total Buku</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_buku"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td colspan='3'><a href='stat_grup.php' style='color: blue;'>Statistik Berdasar Kelompok Buku</a></td>
                </tr>
            </table>
        </td><!--Tabel Kolom Kanan1-->
        <td width='50%' valign='top'>
            <table cellpadding='1' cellspacing='1' width='100%'>
                <tr>
                    <td colspan='3'><b>Hari Ini</b></td>
                </tr>
                <tr>
                    <td>Peminjaman</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php $hrne=date('Y-m-d'); echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE tgl_pinjam LIKE '$hrne%'"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td>Pengembalian</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE tgl_kembali like '$hrne%'"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td colspan='3'><br></td>
                </tr>
                <tr>
                    <td colspan='3'><b>Bulan Ini</b></td>
                </tr>
                <tr>
                    <td>Peminjaman</td>
                    <td>:</td>
                    <?php $bulan_ini=explode(' ',sekarang());  ?>
                    <td>
                        <b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE tgl_pinjam like '".date('Y-m')."%'");  ?>));</script></b>
                    </td>
                    
                </tr>
                <tr>
                    <td>Pengembalian</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE tgl_kembali like '".date('Y-m')."%'"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td colspan='3'><br></td>
                </tr>
                <tr>
                    <td colspan='3'><b>Tahun Ini</b></td>
                </tr>
                <tr>
                    <td>Peminjaman</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php $blne=explode(" ",sekarang()); $thne=$blne['2']; echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE tgl_pinjam like '$thne%'"); ?>));</script></b></td>
                </tr>
                <tr>
                    <td>Pengembalian</td>
                    <td>:</td>
                    <td><b><script type='text/javascript'>document.write(format(<?php $blne=explode(" ",sekarang()); $thne=$blne['2']; echo $peminjam->baris("SELECT * FROM tbl_peminjaman WHERE tgl_kembali LIKE '$thne%'"); ?>));</script></b></td>
                </tr>
            </table>
        </td><!--Tabel Kolom Kiri1-->
    </tr>
</table>
<br><br>
<i class="icon-file"></i> &nbsp;<b>Download (Format *.xls):</b><br><br>
<form action='halaman/exs.php' method='get'>
    <select name='tabel' onchange='submiter.disabled=false'>
        <option value='none' selected='selected'>Pilih Tabel</option>
        <option value='tbl_buku'>Buku</option>
        <option value='tbl_anggota'>Siswa</option>
        <option value='tbl_peminjaman'>Peminjaman</option>
        <option value='tbl_pustakawan'>Anggota</option>
        <option value='tbl_kas'>Catatan kas</option>
        <option value='log'>Catatan Log</option>
    </select>
    <input type='hidden' value='dwn' name='mode'>
    <input type='submit' value='Download' disabled='disabled' name='submiter' class="btn btn-success" style="margin-top:-8px">
</form>
