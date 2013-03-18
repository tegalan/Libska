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
    $log->sql("TRUNCATE log");
    catat($_SESSION['nama'],"Menghapus Log/Aktivitas");
}
$lihat=mysql_real_escape_string($_GET['item']);
$tambah=$lihat+50;
if($lihat==''){
    $lihat='100';
}
$log->sql("SELECT * FROM log ORDER BY id ASC LIMIT 0, $lihat");
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
<table cellpadding='2' cellspacing='0' width='100%' class="table table-striped table-hover">
<thead>
    <tr style='font-weight: bold; '>
        <td width='150' ">User</td>
        <td ">Tindakan / Aktivitas</td>
        <td width='150' ">Waktu</td>
    </tr>
</thead>
<?php
while($log->hasil()){
    echo "<tr id='rec'>";
    echo "<td>".$log->hasil['user']."</td>";
    echo "<td>".$log->hasil['aksi']."</td>";
    echo "<td>".$log->hasil['tgl']."</td>";
    echo "</tr>";
}
echo "<tr><td colspan='3' style=\"padding: 3px\"><button class='btn btn-success' onclick='window.location=\"log.php?item=".$tambah."#bawah\"'>Lihat Lebih Banyak</button>";
echo admin(" <button onclick='reset()' class='btn btn-danger'>Kosongkan Log Aktivitas</button></td></tr>");
echo "</table>";
echo "<hr>";
echo "<div id='bawah'></div>";
get_kaki();

?>