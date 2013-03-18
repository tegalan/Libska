<?php
session_start();
require 'sistem/config.php';
require 'sistem/class_buku.php';

sambung();
        
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
        
    
        $kemarin=$waktu = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+$tglK,date("Y")));
        
        //echo $kemarin."<br>";
        
        $temSql=mysql_query("SELECT * FROM tbl_peminjaman WHERE kembali='0' AND tgl_tempo='$kemarin'");
        $count=0;
        if($tglK<0){
            $book=new buku();
            while($l= mysql_fetch_array($temSql)){
                $sis=$l['siswa'];
                $book->setKode($l['buku']);
                //echo "------------------".$sis."<br>";
                $buku=  mysql_real_escape_string($l['buku']);
                $judul= $book->getJudul();
                $tgl=$l['tgl_tempo'];
                //echo "------------------ $tgl <br>";
                    $si=  mysql_query("SELECT no_induk, nama FROM tbl_anggota WHERE no_induk='$sis'");
                    $sisw=  mysql_fetch_array($si);
                    $sis = mysql_real_escape_string($sisw['nama']);
                    $indk= $sisw['no_induk'];
                
                    $key=md5($sis.$buku.$tgl);
                
                $c=  mysql_query("SELECT * FROM tbl_telat WHERE kunci = '$key'");
                $ce=  mysql_num_rows($c);
                    if($ce == '0'){
                        
//                        echo "Belum ada record<br>";    
//                        echo "Kode $key | $sis memijam buku $buku dengan judul $judul dan jatuh tempo pada tanggal $tgl<br><br>";
                        $insert=mysql_query("INSERT into tbl_telat SET induk=\"$indk\", buku=\"$buku\", siswa=\"$sis\", tanggal=\"$tgl\", judul=\"$judul\", kunci=\"$key\"");
                        if(!$insert){
                            $count++;
                            echo "<script type='text/javascript'>alert(\"Gagal Menyimpan jatuh Tempo\"); console.log(\"".mysql_error()."\");</script>";
                        }
            
                    }
                
            }
        }
        $sync=true;
    }
    
if($count=="0"){
    echo "<div id='sync' style='padding: 5px;'><a href='index.php'>Data jatuh tempo sudah di sinkronisasi</a></div>";
}else{
    echo "<a href='index.php'>Ada <b>$count</b> data jatuh tempo, klik di SINI untuk melihat</a>";
}
//echo "Total $count";
?>
