<?php
session_start();
/*
 * @author :   Sucipto
 * @site   :   www.sucipto.net
 */
require "../sistem/config.php";
cek_user();
sambung();
//Step 1. Import Struktur Tabel Baru
$warning=null;
$upgrade_file="upgrade.sql";
if(file_exists($upgrade_file)){
    $file=  file_get_contents($upgrade_file);
    $templine = '';
    $letak=$upgrade_file;
    $lines = file($letak);
    $berhasil=0; $gagal=0;
    // Loop through each line
    foreach ($lines as $line)
    {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                    continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                    // Perform the query
                    if(mysql_query($templine)){
                            $berhasil++;
                    }else{
                            $gagal++;
                    }
                    // Reset temp variable to empty
                    $templine = '';

            }
    }
    if(!empty($gagal)){
        $warning.="Gagal Upgrade SQL QUERY ERROR : ".mysql_error();
    }
}else{
    $warning.="File upgrade.sql tidak ditemukan, mohon periksa kembali";
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
    <div class="container" style="margin-top: 20px;">
        <div class="well">
            <h1>System Upgrade</h1>
            <p>Langkah ini digunakan untuk mengupgrade Database dari Libska Versi 3.0.0 ke versi 3.1.0</p>
            <p>Mohon Backup dahulu database sebelum melakukan proses Upgrade. Data Log Aktivitas akan dihapus semua.</p>
            <?php
                if(!empty($warning)){
                    echo "<p class='alert'>$warning</p>";
                }
            ?>
            <a href="upgrade.php" class="btn btn-info">Lanjutkan >></a>
        </div>
    </div>
</body>
</html>