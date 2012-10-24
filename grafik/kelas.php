<?php
include '../sistem/graph/jpgraph.php';
include '../sistem/graph/jpgraph_pie.php';
include '../sistem/graph/jpgraph_pie3d.php';
include '../sistem/config.php';
$droti=array();
$dkel=array();
$kelas=array('TEI 2','TEI 1', 'TKJ 2', 'TKJ 1', 'PM 2', 'PM 1', 'AK 2', 'AK 1', 'AP 2', 'AP 1');

$jkel=count($kelas)-1;
$i=0;
sambung();
$db=new db();
if($_GET['kelas']==''){
    exit(0);
}
//Roti kelas x
$kelase=mysql_real_escape_string($_GET['kelas']);
    while($i<=$jkel){
    $jur=$kelas[$i];
    $jum=$db->baris("select * from siswa where jurusan='$jur' && kelas like '$kelase' && count_pinjam !='0'");
    //echo "$jur = $jum<br>";
    array_unshift($droti,$jum);
    array_unshift($dkel,"$kelase ".$jur);
    $i++;
    }
//print_r($droti);
//echo "<br>";
//print_r($dkel);
/**/
if(array_sum($droti)=='0'){
    $droti=array('1');
    $dkel=array('Data Kosong !!');
}
$roti= new PieGraph(400,400);
$roti->title->Set("Peminjaman Berdasarkan Kelas (Kelas $kelase) | Libska");

$irisan=new PiePlot($droti);
$irisan->SetTheme('sand');
$roti->Add($irisan);
$irisan->SetLegends($dkel);
$irisan->ShowBorder();
$roti->Stroke();/**/
?>