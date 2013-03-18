<?php

/*
 * @author :   Sucipto
 * @site   :   www.sucipto.net
 */
include "../sistem/config.php";
include "exelreader.php";

//Sambung ke database
sambung();
//Proses Improt
$mode=$_POST['import'];
if($mode!="" || $mode!=null){
    // membaca file excel yang diupload
    
    $data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
    
    // membaca jumlah baris dari data excel
    $baris = $data->rowcount($sheet_index=0);
    $col = $data->colcount($sheet_index=0);
    
    // nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
    $sukses = 0;
    $gagal = 0;
    
    //Lets do tthe harlem shake :D
    for ($i=2; $i<=$baris; $i++)
    {
      // membaca data nim (kolom ke-1)
      $satu = $data->val($i, 1);//No induk / Kd buku
      $dua= $data->val($i, 2); //
      $tiga= $data->val($i, 3);
      $empat= $data->val($i, 4);
      $lima= $data->val($i, 5);
      $enam= $data->val($i, 6);
      //echo "$satu | $dua | $tiga | $empat | $lima | $enam<br>";
      
      //Insert Ke database
      if($mode=='anggota'){
          $sql="INSERT INTO tbl_anggota SET no_induk=\"$satu\", nama=\"$dua\", kelas=\"$tiga\", jurusan=\"$empat\"";
        if (mysql_query($sql)){
            $sukses++;
        }else{
            $gagal++;
            $error.=mysql_error()."<br>";
        }
      }elseif($mode=="buku"){
        $sql="INSERT INTO tbl_buku SET kd_buku=\"$satu\", judul=\"$dua\", pengarang=\"$tiga\", thn_terbit=\"$empat\", penerbit=\"$lima\", harga=\"$enam\", status=\"1\"";
        if (mysql_query($sql)){
            $sukses++;
        }else{
            $gagal++;
            $error.=mysql_error()."<br>";
        }
      }
    }

}
?>
<html lang="en">
<head>
    <title>Perpustakaan <?php echo get_sistem("nama"); ?> | LIBSKA <?php echo getVersion(); ?></title>
    <link href="../tampilan/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src='../tampilan/jq.js' type='text/javascript'></script>
    <script src="../tampilan/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="well" style="margin-top: 20px;">
            <?php if($_GET['target']=='anggota'): ?>
            <h1>Import Data Anggota</h1>
            <form method="post" enctype="multipart/form-data" action="index.php">
            <input type="hidden" name="import" value="anggota">
            Silakan Pilih File Excel: 
            <input name="userfile" type="file">
            <input class="btn btn-info" name="upload" type="submit" value="Import">
            </form> 
            <p class="alert alert-warning">Catatan: Format import harus sesuai standart aplikasi Libska <?php echo getVersion(); ?>.<br><br><a href="import_anggota.xls" class="btn btn-success">Download Format Import</a></p>
            <?php elseif ($_GET['target']=='buku'): ?>
            <h1>Import Data Buku</h1>
            <form method="post" enctype="multipart/form-data" action="index.php">
            <input type="hidden" name="import" value="buku">
            Silakan Pilih File Excel: 
            <input name="userfile" type="file">
            <input class="btn btn-info" name="upload" type="submit" value="Import">
            </form> 
            <p class="alert alert-warning">Catatan: Format import harus sesuai standart aplikasi Libska <?php echo getVersion(); ?>.<br><br><a href="import_buku.xls" class="btn btn-success">Download Format Import</a></p>
            <?php endif; ?>
            <?php if(!empty($mode)): ?>
            <p class="alert alert-success">Berhasil di import <?php echo $sukses ?></p>
            <p class="alert alert-success">Gagal di import <?php echo $gagal."<br>".$error ?></p>
            <a href="../index.php" class='btn btn-info'><< Kembali</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>