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
    $tahun=date('Y');
}else{
    $tahun=$_GET['thn'];
}
/*Ambil data pinjaman*/
while($i<=$jbulan){
    $bul= sprintf('%02d',$i+1);
    $wul= $bulan[$i];
    $jpin=$db->baris("SELECT * FROM tbl_peminjaman WHERE tgl_pinjam LIKE '%-$bul-%' && tgl_pinjam LIKE '$tahun%'");
//    echo "SELECT * FROM tbl_peminjaman WHERE tgl_pinjam LIKE '%-$bul-%' && tgl_pinjam LIKE '$tahun%'<br>";
    $jkem=$kem->baris("SELECT * FROM tbl_peminjaman WHERE tgl_kembali LIKE '%-$bul-%' && tgl_kembali LIKE '$tahun%'");
//    echo "SELECT * FROM tbl_peminjaman WHERE tgl_kembali LIKE '%-$bul-%' && tgl_kembali LIKE '$tahun%'<br><br>";
    //echo "$wul sebanyak $jpin <br><br>";
    array_unshift($w,$wul);
    
    array_unshift($datay,$jpin);
    
    array_unshift($datax,$jkem);
    //array_unshift($datax,$jpin);
    $i++;
}
//echo "<pre>";
$datax=  array_reverse($datax);
//print_r($datax);
$datay= array_reverse($datay);
//print_r($datay);
//echo "</pre>";

/*Panggil Pembuat Grafik*/
$g= new Graph(950,300,"auto");
$g->SetScale("textlin");
$g->SetShadow();
$g->img->SetMargin(40,30,30,40);
$g->title->Set("Prosentase Peminjaman Tahun $tahun");
//$g->xaxis->title->set("Bulan");
$g->yaxis->title->Set("Jumlah");
$g->xaxis->SetTickLabels($w);
/*Membuat Data Batang*/

$pinjam= new BarPlot($datay);
$pinjam->SetLegend("Peminjaman");
$pinjam->value->show();

$kmbli=new BarPlot($datax);
$kmbli->SetLegend("Pengembalian");
$kmbli->value->show();

$grup=new GroupBarPlot(array($pinjam, $kmbli));

$g->Add($grup);
$pinjam->SetFillColor("#6633ff");
$kmbli->SetFillColor("#ff6633");
//$g->Add($pinjam);
/*Cetak gambar*/
$g->Stroke();
?>