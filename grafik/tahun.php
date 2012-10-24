<?php
include '../sistem/graph/jpgraph.php';
include '../sistem/graph/jpgraph_bar.php';
include '../sistem/config.php';
$datay=array();
$datax=array();
$w=array();
/*Mengambil Data dari database*/
$bulan=array('Desember','Nopember','Oktober','September','Agustus','Juli','Juni','Mei','April','Maret','Pebruari','Januari');
$wulan=array('Des','Nop','Okt','Sep','Agu','Jul','Jun','Mei','Apr','Mar','Peb','Jan');
sambung();
$db=new db();
$kem=new db();
$jbulan=count($bulan)-1;
$i=0;
if($_GET['thn']==''){
$tahun=substr(sekarang(),-4);
}else{
$tahun=$_GET['thn'];
}
/*Ambil data pinjaman*/
while($i<=$jbulan){
    $bul= $bulan[$i];
    $wul= $bulan[$i];
    $jpin=$db->baris("select * from pinjaman where tgl_pinjam like '%$bul%' && tgl_pinjam like '%$tahun'");
    
    $jkem=$kem->baris("select * from pinjaman where kembaline like '%$bul%' && kembaline like '%$tahun'");
    
    //echo "$wul sebanyak $jpin <br><br>";
    array_unshift($w,$wul);
    
    array_unshift($datay,$jpin);
    
    array_unshift($datax,$jkem);
    //array_unshift($datax,$jpin);
    $i++;
}


/*Panggil Pembuat Grafik*/
$g= new Graph(950,300,"auto");
$g->SetScale("textlin");
$g->SetShadow();
$g->img->SetMargin(40,30,30,40);
$g->title->Set("Prosentase Peminjaman");
//$g->xaxis->title->set("Bulan");
$g->yaxis->title->Set("Jumlah Peminjaman");
$g->xaxis->SetTickLabels($w);
/*Membuat Data Batang*/

$pinjam= new BarPlot($datay);
$pinjam->SetFillColor("orange");
$pinjam->SetLegend("Peminjaman");
$pinjam->value->show();

$kmbli=new BarPlot($datax);
$kmbli->SetFillColor("blue");
$kmbli->SetLegend("Pengembalian");
$kmbli->value->show();

$grup=new GroupBarPlot(array($pinjam, $kmbli));

$g->Add($grup);
//$g->Add($pinjam);
/*Cetak gambar*/
$g->Stroke();
?>