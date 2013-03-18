<?php
session_start();
/*
File: siswa.php => anggota.php
Fungsi: Melihat daftar siswa.
Auth: ShowCheap
*/
require 'sistem/config.php';
sambung();
get_kepala();
cek_user();

$siswa=new db();

/*Hapus*/
if(isset($_GET['hapus'])){
    $ide=$_GET['hapus'];
    mysql_query("DELETE FROM tbl_anggota WHERE no_induk='$ide'");
    catat($_SESSION['nama'], "Menghapus dg No Induk $ide");
}
//Naik Kelas
//if(isset($_POST['naikt'])){
//    echo mysql_error();
//    if($_POST['pwd']=='libska'){
//       //proses perpindahan kelas
//       $lulusan=date('Y');
//       $first=mysql_query("update siswa set kelas=\"Al $lulusan\" where kelas=\"XII\"");
//       if($first){
//        echo "<script type='text/javascript'>alert(\"Kelas XII => Alumni\");</script>";
//        //Jalankan langkah 2 jika langkah 1 berhasil
//        $second=mysql_query("update siswa set kelas=\"XII\" where kelas=\"XI\"");
//        if($second){
//            echo "<script type='text/javascript'>alert(\"Kelas XI => XII\");</script>";
//            //jalankan langkah ketiga
//            $third=mysql_query("update siswa set kelas=\"XI\" where kelas=\"X\"");
//            if($third){
//                echo "<script type='text/javascript'>alert(\"Kelas X => XI\");</script>";
//            }else{
//                echo "<script type='text/javascript'>alert(\"Kelas X Gagal di Eksekusi\");</script>";
//            }
//        }else{
//            echo "<script type='text/javascript'>alert(\"Kelas XI Gagal di Eksekusi\");</script>";
//        }
//       }else{
//        echo "<script type='text/javascript'>alert(\"Kelas XII Gagal di Eksekusi".mysql_error()."\");</script>";
//       }
//        
//        
//        
//    }else{
//        echo "<center>Kode Salah</center>";
//    }
//}
//New Naik Kelas
if($_GET['naik']=='kelas'){
    $lulusan=date('Y');
    $first=mysql_query("UPDATE tbl_anggota SET kelas=\"Alumni$lulusan\" WHERE kelas=\"XII\"");
    if($first){
        $second=mysql_query("UPDATE tbl_anggota SET kelas=\"XII\" WHERE kelas=\"XI\"");
        if($second){
            $third=mysql_query("UPDATE tbl_anggota SET kelas=\"XI\" where kelas=\"X\"");
            if($third){
                echo "<p>Semua anggota (siswa) sudah naik kelas, :D</p><p>Kamu sekarang dapat melakukan import data siswa baru.</p><br><a href='anggota.php' class='btn btn-success'> << Kembali</a> <a href='import/index.php?target=anggota' class='btn btn-info'>Import Data >> </a>";
            }else{
                echo "FIRST ERROR: ".mysql_error();
            }
        }else{
            echo "SECOND ERROR: ".mysql_error();
        }
    }else{
        echo "FIRST ERROR: ".mysql_error();
    }
    
    exit(0);
}

/*Data Penambahan Siswa*/
$induk=mysql_real_escape_string($_POST['no_induk']);
$nama=mysql_real_escape_string($_POST['nama']);
$class=$_POST['kelas'];
$voc=$_POST['jurusan'];
if($_POST['tambah'] && $induk!='' && $nama!=''){
    $try=mysql_query("INSERT INTO tbl_anggota SET no_induk = '$induk', nama='$nama', kelas='$class', jurusan='$voc'");
    if($try){
        //echo "<script type='text/javacsript'>alert('Lunasss')</script>";
        catat($_SESSION['nama'], "Berhasil Menambah $nama");
        echo "<script type='text/javascript'>alert('Berhasil Di Tambahkan !!')</script>";
    }else{
        //echo "<script type='text/javacsript'>alert('Gagaasdfsdfl Di Tambahkan !!')</script>";
        catat($_SESSION['nama'], "Gagal Menambah $nama");
        echo "<script type='text/javascript'>alert('Gagal Di Tambahkan !!')</script>";
    }
}



/*Ambil data Form*/
$murni=$_GET['cari'];
$cari=mysql_real_escape_string($_GET['cari']);

$dari=$_GET['dari'];
    if(!isset($dari)){
        $dari='no_induk';
    }

$kelas=$_GET['kelas'];
if($kelas==''){
    $kelas='%%';
}


$jur=$_GET['jurusan'];

$lim=$_GET['jumlah'];

if(isset($lim)){
    $s="limit 0,";
    $limit= $s.$lim;
}else{
    $limit="limit 0,40";
}

$siswa->sql("SELECT * FROM tbl_anggota WHERE $dari LIKE '%$cari%' && kelas LIKE '$kelas' && jurusan LIKE '%$jur%' $limit");


?>

<style>
   tr#row:hover{
    background-color: gray;
    color: white;
   }
</style>
<script type='text/javascript'>
    function tambah(){
        document.forms["frm-tambah"]["no_induk"].disabled=false;
        document.forms["frm-tambah"]["nama"].disabled=false;
        document.forms["frm-tambah"]["kelas"].disabled=false;
        document.forms["frm-tambah"]["jurusan"].disabled=false;
        document.forms["frm-tambah"]["tambah"].disabled=false;
    }
    function submiter(){
        document.forms['cari'].submit();
    }
</script>
<script>
$(document).ready(function(){
    $(".hide").hide();
    $("#button-add").click(function(){
        $("#form-add").slideToggle();
    })
    $("#tombol-naik,#tidak").click(function(){
        $("#naik-kelas").slideToggle();
    })
})
</script>
<h2 style="border-bottom: 1px solid #e2d9d9">Data Siswa</h2>

<table width='100%' cellpadding='3' cellspacing='1' name='siswa' style='font-size: 12px;' class="table table-hover">
    <thead>
    <tr>
        <td colspan='6'>
            <button id="button-add" onclick='tambah()' class='btn btn-info'><i class='icon-plus icon-white'></i> Tambah</button>
            <a href="import/index.php?target=anggota" class="btn btn-info"><i class=' icon-download-alt icon-white'> </i> Import Data</a>
            <a id="tombol-naik" href="#naik" class='btn btn-info'><i class='icon-user icon-white'></i> Naik Tingkat</a>
            <!--form tambah-->
            <form class="hide" id="form-add" action='' method='post' name='frm-tambah' style='margin-top: 5px;'>
                <input type='text' name='no_induk' class="span2" title='Nomor Induk' disabled='disabled' placeholder="No Induk / No Anggota"><br>
                <input type='text' name='nama' title='Nama Siswa' disabled='disabled' placeholder="Nama Siswa"><br>
                <select name='kelas' disabled='disabled' style="width: 100px;">
                    <option value='X'>X</option>
                    <option value='XI'>XI</option>
                    <option value='XII'>XII</option>                    
                    <option value='Karyawan'>Karyawan</option>
                </select><br>
                <select name='jurusan' disabled='disabled'>
                    
                    <optgroup label='Bisman'>
                        <option value='AP'>Administrasi Perkantoran</option>
                        <option value='AK'>Akuntansi</option>
                        <option value='PM'>Pemasaran</option>
                    </optgroup>
                    <optgroup label='Teknik'>
                        <option value='TKJ'>Teknik Komputer Jaringan</option>
                        <option value='TEI'>Teknik Elektronika Industri</option>
                    </optgroup>
                    <optgroup label='Karyawan' id='tu'>
                        <option value='GURU'>Guru</option>
                        <option value='TU'>Tata Usaha</option>
                    </optgroup>                   
                </select><br>
                <input style="margin-top: -10px;" class="btn btn-info" type='submit' value='Tambah' name='tambah' disabled='disabled'>
            </form>
            <!--end form tambah-->
            <div id="naik-kelas" class="hide" style="padding: 5px;">
                <p>Fungsi ini akan mengubah tingkat kelas (misal: X menjadi XI, XI menjadi XII, XII berubah menjadi alumni). Anda Yakin?</p>
                <a href="?naik=kelas" class="btn btn-success">Yakin</a>
                <a id="tidak" href="#a" class="btn btn-danger">Tidak</a>
            </div>
            <!--end naik kelas-->
        </td>
    </tr>
    
    </thead>
    
    <tr>
        <td colspan='6'>
            <form action='' method='get' name='cari'>
                <input type='text' name='cari' value='<?php echo $murni; ?>' onchange='submiter()'>
                <select name='dari' id='frm-cari' onchange='submiter()'>
                    <option value='no_induk'>No Induk</option>
                    <option value='nama' <?php if($dari=='nama') echo "selected='selected'"; ?> >Nama</option>
                </select>
                <select name='kelas' onchange='submiter()' style="width: 100px;">
                    <option value=''>Semua</option>
                    <option value='X' <?php if($kelas=='X') echo "selected='selected'"; ?>>X</option>
                    <option value='XI' <?php if($kelas=='XI') echo "selected='selected'"; ?>>XI</option>
                    <option value='XII' <?php if($kelas=='XII') echo "selected='selected'"; ?>>XII</option>
                    <option value='Alumni%' <?php if($kelas=='Alumni%') echo "selected='selected'"; ?>>Alumni</option>
                    <option value='Karyawan' <?php if($kelas=='Karyawan') echo "selected='selected'"; ?>>Karyawan</option>
                </select>
                <select name='jurusan' onchange='submiter()'>
                    <option value=''>Semua</option>
                    <optgroup label='Bisman'>
                        <option value='AP' <?php if($jur=='AP') echo "selected='selected'"; ?>>Administrasi Perkantoran</option>
                        <option value='AK' <?php if($jur=='AK') echo "selected='selected'"; ?>>Akuntansi</option>
                        <option value='PM' <?php if($jur=='PM') echo "selected='selected'"; ?>>Pemasaran</option>
                    </optgroup>
                    <optgroup label='Teknik'>
                        <option value='TKJ'  <?php if($jur=='TKJ') echo "selected='selected'"; ?>>Teknik Komputer Jaringan</option>
                        <option value='TEI'  <?php if($jur=='TEI') echo "selected='selected'"; ?>>Teknik Elektronika Industri</option>
                    </optgroup>
                    <optgroup label='Karyawan'>
                        <option value='GURU' <?php if($jur=='GURU') echo "selected='selected'"; ?>>Guru</option>
                        <option value='TU' <?php if($jur=='TU') echo "selected='selected'"; ?>>Tata Usaha</option>
                    </optgroup>  
                </select>
                <select name='jumlah' onchange='submiter()'  style="width: 100px;">
                    <option>40</option>
                    <option>80</option>
                    <option>120</option>
                    <option>200</option>
                    <option value='<?php echo $siswa->baris("SELECT * FROM tbl_anggota"); ?>'>Semua</option>
                </select>
                <input style="margin-top: -9px;" class="btn btn-info" type='submit' value='OK' name='ok'>
            </form>
        </td>
    </tr>
    <tr>
        <td>No</td><td>No Induk</td><td>Nama</td><td>Kelas</td><td>Meminjam</td><td>Menu</td>
    </tr>
    <tbody>
    <?php
    $no=1;
    while($siswa->hasil()){
       echo "<tr id='rows'>";
        echo "<td>$no</td>";
        echo "<td>".$siswa->hasil['no_induk']."</td>";
        echo "<td><a href='peminjaman.php?pencarian=".$siswa->hasil['no_induk']."'>".$siswa->hasil['nama']."</a></td>";
        echo "<td>".$siswa->hasil['kelas']." ".$siswa->hasil['jurusan']."</td>";
        echo "<td>".$siswa->hasil['count']."</td>";
        $sis=$siswa->hasil['no_induk'];
        echo "<td>".admin("<a class='btn btn-danger' href='anggota.php?hapus=$sis' onclick='return confirm(\"Yakin Hapus?\");'>Hapus</a>")."</td>";
       echo "</tr>";
       $no++;
    }
    
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan='6' style='background-color: silver;'>&nbsp;</td>
    </tr>
    </tfoot>
</table>
<hr>

<?php get_kaki(); ?>