<?php
session_start();
/*
File: log.php
Fungsi: File untuk melihat aktivitas aplikasi.
Auth: ShowCheap
*/
require 'sistem/config.php';
cek_user();
sambung();
get_kepala();

$log=new db();
if(isset($_GET['reset']) && isset($_SESSION['level'])){
    $log->sql("truncate log");
    catat($_SESSION['nama'],"Menghapus Log/Aktivitas");
}
$lihat=mysql_real_escape_string($_GET['item']);
$tambah=$lihat+50;
if($lihat==''){
    $lihat='100';
}
$log->sql("select * from log order by id ASC limit 0, $lihat");
?>
<script type='text/javascript'>
    function reset(){
        var tanya = confirm('Yakin Reset Semua Aktivitas??');
        if(tanya){
            window.location='?reset';
        }
    }
</script>
<style>
    #rec:hover{
        background-color: silver;
    }
</style>
<h2>Log / Aktivitas</h2>
<table cellpadding='2' cellspacing='0' width='100%' style='border: 1px solid; padding: 2px;'>
    <tr style='font-weight: bold; '>
        <td width='150' style="border: 1px solid; margin: 0;">User</td>
        <td style="border: 1px solid; margin: 0;">Tindakan / Aktivitas</td>
        <td width='150' style="border: 1px solid; margin: 0;">Waktu</td>
    </tr>

<?php
while($log->hasil()){
    echo "<tr id='rec'>";
    echo "<td style='border-bottom: 1px solid blue'>".$log->hasil['user']."</td>";
    echo "<td style='border-bottom: 1px solid blue'>".$log->hasil['aksi']."</td>";
    echo "<td style='border-bottom: 1px solid blue'>".$log->hasil['tgl']."</td>";
    echo "</tr style='border-bottom: 1px solid blue'>";
}
echo "<tr><td colspan='3'><button onclick='window.location=\"log.php?item=".$tambah."#bawah\"'>Lihat Lebih Banyak</button>";
echo admin("<button onclick='reset()'>Kosongkan Log Aktivitas</button></td></tr>");
echo "</table>";
echo "<hr>";
echo "<div id='bawah'></div>";
get_kaki();

?>