<?php
session_start();
/*
File: siswa.php
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
    mysql_query("delete from siswa where no_induk='$ide'");
    catat($_SESSION['nama'], "Menghapus dg No Induk $ide");
}
//Naik Kelas
if(isset($_POST['naikt'])){
    echo mysql_error();
    if($_POST['pwd']=='libska'){
       //proses perpindahan kelas
       $lulusan=date('Y');
       $first=mysql_query("update siswa set kelas=\"Al $lulusan\" where kelas=\"XII\"");
       if($first){
        echo "<script type='text/javascript'>alert(\"Kelas XII => Alumni\");</script>";
        //Jalankan langkah 2 jika langkah 1 berhasil
        $second=mysql_query("update siswa set kelas=\"XII\" where kelas=\"XI\"");
        if($second){
            echo "<script type='text/javascript'>alert(\"Kelas XI => XII\");</script>";
            //jalankan langkah ketiga
            $third=mysql_query("update siswa set kelas=\"XI\" where kelas=\"X\"");
            if($third){
                echo "<script type='text/javascript'>alert(\"Kelas X => XI\");</script>";
            }else{
                echo "<script type='text/javascript'>alert(\"Kelas X Gagal di Eksekusi\");</script>";
            }
        }else{
            echo "<script type='text/javascript'>alert(\"Kelas XI Gagal di Eksekusi\");</script>";
        }
       }else{
        echo "<script type='text/javascript'>alert(\"Kelas XII Gagal di Eksekusi".mysql_error()."\");</script>";
       }
        
        
        
    }else{
        echo "<center>Kode Salah</center>";
    }
}

/*Data Penambahan Siswa*/
$induk=mysql_real_escape_string($_POST['no_induk']);
$nama=mysql_real_escape_string($_POST['nama']);
$class=$_POST['kelas'];
$voc=$_POST['jurusan'];
if($_POST['tambah'] && $induk!='' && $nama!=''){
    $try=mysql_query("insert into siswa set no_induk = '$induk', nama='$nama', kelas='$class', jurusan='$voc'");
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

$siswa->sql("select * from siswa where $dari like '%$cari%' && kelas like '$kelas' && jurusan like '%$jur%' $limit");


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
<h2>Data Siswa</h2>
<fieldset style="border: 1px solid; margin-bottom: 2px; margin: 0;;">
<legend>Kenaikan Kelas</legend>

<form action="" method="post" name='naik-kelas'>
    Kode:<input type='password' name='pwd' id='naik-pwd' onclick='document.forms["naik-kelas"]["naikt"].disabled=false;'>
    <input onclick="return confirm('Yakin Melakukan Proses ini?\nIni akan merubah data kelas siswa secara otomatis.\n\nLanjutkan?')" id='naik-tmbl' name='naikt' type='submit' value='Naik Kelas' disabled='disabled'>
</form>
</fieldset>
<table width='100%' cellpadding='3' cellspacing='1' name='siswa' style='font-size: 12px; border: 1px solid;'>
    <tr>
        <td colspan='6'>
            <b onclick='tambah()'>Tambah ?</b>
            <form action='' method='post' name='frm-tambah'>
            
                <input type='text' name='no_induk' size='10' title='Nomor Induk' disabled='disabled'>
                <input type='text' name='nama' title='Nama Siswa' disabled='disabled'>
                <select name='kelas' disabled='disabled'>
                    <option value='X'>X</option>
                    <option value='XI'>XI</option>
                    <option value='XII'>XII</option>
                    <option value='Karyawan'>Karyawan</option>
                </select>
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
                </select>
                <input type='submit' value='+' name='tambah' disabled='disabled'>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan='6'></td>
    </tr>
    <tr>
        <td colspan='6' style='border: 1px solid;'>
            <form action='' method='get' name='cari'>
                <input type='text' name='cari' value='<?php echo $murni; ?>' onchange='submiter()'>
                <select name='dari' id='frm-cari' onchange='submiter()'>
                    <option value='no_induk'>No Induk</option>
                    <option value='nama' <?php if($dari=='nama') echo "selected='selected'"; ?> >Nama</option>
                </select>
                <select name='kelas' onchange='submiter()'>
                    <option value=''>Semua</option>
                    <option value='X' <?php if($kelas=='X') echo "selected='selected'"; ?>>X</option>
                    <option value='XI' <?php if($kelas=='XI') echo "selected='selected'"; ?>>XI</option>
                    <option value='XII' <?php if($kelas=='XII') echo "selected='selected'"; ?>>XII</option>
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
                <select name='jumlah' onchange='submiter()'>
                    <option>40</option>
                    <option>80</option>
                    <option>120</option>
                    <option>200</option>
                    <option value='<?php echo $siswa->baris("select * from siswa"); ?>'>Semua</option>
                </select>
                <input type='submit' value='OK' name='ok'>
            </form>
        </td>
    </tr>
    <tr style='background-color: silver; '>
        <td style='border: 1px solid;'>No</td><td style='border: 1px solid;'>No Induk</td><td style='border: 1px solid;'>Nama</td><td style='border: 1px solid;'>Kelas</td><td style='border: 1px solid;'>Meminjam</td><td style='border: 1px solid;'>Menu</td>
    </tr>
    <?php
    $no=1;
    while($siswa->hasil()){
       echo "<tr id='row'>";
       echo "<td style='border: 1px solid #C0C0C0;'>$no</td>";
       echo "<td style='border: 1px solid #C0C0C0;'>".$siswa->hasil['no_induk']."</td>";
       echo "<td style='border: 1px solid #C0C0C0;'><a href='peminjaman.php?pencarian=".$siswa->hasil['no_induk']."'>".$siswa->hasil['nama']."</a></td>";
       echo "<td style='border: 1px solid #C0C0C0;'>".$siswa->hasil['kelas']." ".$siswa->hasil['jurusan']."</td>";
       echo "<td style='border: 1px solid #C0C0C0;'>".$siswa->hasil['count_pinjam']."</td>";
       $sis=$siswa->hasil['no_induk'];
        echo "<td style='border: 1px solid #C0C0C0;'>".admin("<a href='siswa.php?hapus=$sis' onclick='return confirm(\"Yakin Hapus?\");'>Hapus</a>")."</td>";
       echo "</tr>";
       $no++;
    }
    
    ?>
    <tr>
        <td colspan='6' style='background-color: silver;'>&nbsp;</td>
    </tr>
</table>
<hr>
<?php get_kaki(); ?>