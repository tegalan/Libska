<?php
session_start();
require 'sistem/config.php';

sambung();


///Di ulang 7x (seminggu)
//$sy=$_SESSION['sync'];
//$s=sekarang();
//if($sy!=$s or $sy==''){
        
    for($i=1; $i<=120; $i++){
        
        $tglK=sekarang(tgl)-$i;        
        
        /*Tanggal*/
       $hari=date('d');
       $bulan=date('M');
       $tahun=date('Y');
       $indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
       $bln=$indo[$bulan+1];
       $hari_ini=sekarang();//"$hari $bln $tahun";
       //**tgl kembali**//
       $waktu = date("d-n-Y", mktime(0,0,0,date("m"),date("d")+$tglK,date("Y")));
       $x=explode('-', $waktu);
       $hari2=$x['0']; 
       $bulan2=$x['1'];
       $tahun2=date('Y');
       $bln2=$indo[$bulan2-1];
       //**waktu kembali yg sudah fix**//
       $kembali="$hari2 $bln2 $tahun2";
       
        //run
        
    
        $kemarin=$kembali;
        
        //echo $kemarin."<br>";
        
        $temSql=mysql_query("select * from pinjaman where kembali='0' AND tgl_kembali='$kemarin'");
        $count=0;
        if($tglK<0){
            //echo "Sekarang tanggal $tglK <br>";
            //echo $kembali."<br>";
            while($l= mysql_fetch_array($temSql)){
                $sis=$l['siswa'];
                //echo "------------------".$sis."<br>";
                $buku=  mysql_real_escape_string($l['buku']);
                $judul=  mysql_real_escape_string($l['judul']);
                $tgl=$l['tgl_kembali'];
                //echo "------------------ $tgl <br>";
                    $si=  mysql_query("select no_induk, nama from siswa where no_induk='$sis'");
                    $sisw=  mysql_fetch_array($si);
                    $sis = mysql_real_escape_string($sisw['nama']);
                    $indk= $sisw['no_induk'];
                
                    $key=md5($sis.$buku.$tgl);
                
                $c=  mysql_query("select * from tempo where kunci = '$key'");
                $ce=  mysql_num_rows($c);
                    if($ce == '0'){
                        $count++;
                        //echo "Belum ada record<br>";    
                        //echo "Kode $key | $sis memijam buku $buku dengan judul $judul dan jatuh tempo pada tanggal $tgl<br><br>";
                        $insert=mysql_query("insert into tempo set induk='$indk', buku='$buku', siswa='$sis', tanggal='$tgl', judul='$judul', kunci='$key'");
                        if(!$insert){
                            echo "<script type='text/javascript'>alert(\"Gagal Menyimpan jatuh Tempo\");</script>";
                        }
            
                    }
                
            }
        }
        $sync=true;
    }
    
    //if($sync){
      //  $_SESSION['sync']=sekarang();
    //}
//}else{
//    echo "Jatuh Tempo Sudah sinkron hari ini <a><img></a>";
//}
if($count=="0"){
    echo "<div id='sync' style='padding: 5px;'><a href='index.php'>Data jatuh tempo sudah di sinkronisasi</a></div>";
}else{
    echo "<a href='index.php'>Ada <b>$count</b> data jatuh tempo, klik di SINI untuk melihat</a>";
}
//echo "Total $count";
?>
