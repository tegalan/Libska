<?php
/*
File: fungsi.php
Fungsi: Library Fungsi
Auth: ShowCheap
*/
function catat($us,$text){
    $now=sekarang()." ".date('H:i');
    $peng=mysql_real_escape_string($us);
    $aksi=mysql_real_escape_string($text);
        $insert=mysql_query("insert into log set user = '$peng', aksi = '$aksi', tgl = '$now'");
        if($insert){
            
        }else{
            echo "<script type='text/javascript'>alert('Pencatatan Log gagal');</script>";
        }
}

class db{
    private $h,$u,$p,$d,$query,$baris;
    public $hasil;
    
    public function setDB($ho, $us, $pw, $db){    
        $this->h=$ho;
        $this->u=$us;
        $this->p=$pw;
        $this->d=$db;
    }
    public function sambungDB(){
        @mysql_connect($this->h,$this->u,$this->p);
        @mysql_selectdb($this->d);
    }
    public function sql($query){
        //$this::sambungDB();
        
        $this->query=mysql_query($query);
    }
    public function hasil(){
        $this->hasil=mysql_fetch_array($this->query);
        return $this->hasil;
    }
    public function getJml(){
        //$this::sambungDB();
        return mysql_num_rows($this->query);
    }
    public function baris($sql){
        //$this::sambungDB();
        $this->baris=mysql_query($sql);
        return mysql_num_rows($this->baris);
    }
    public function single($sql){
        $query=mysql_query($sql);
        return @mysql_result($query,0);
    }
}
/*fungsi Hari Ini */
function sekarang($mode='sekarang', $hari='1', $bulan='1', $tahun='1', $indo='Januari', $bln='1', $hari_ini='senin', $sekarang='jam'){
    $hari=date('d');
    $bulan=date('m');
    $tahun=date('Y');
    $indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
    $bln=$indo[$bulan-1];
    $hari_ini="$hari $bln $tahun";
    $sekarang=date('H:i:s');
        if($mode=='jam'){
            return $sekarang;
        }elseif($mode=='tgl'){
            return $hari;
        }elseif($mode=='bln'){
            return $bln;
        }elseif($mode=='thn'){
            return $tahun;
        }else{
            return $hari_ini;
        }
}

function get_kepala(){
    require_once './tampilan/kepala.php';
}

function get_kaki(){
    require_once './tampilan/kaki.php';
}

function cek_user($user='Admin', $or='Pustakawan', $redir='masuk.php'){
    $sesi=$_SESSION['level'];    
    if(!($sesi==$user || $sesi==$or)){
        header('location: '.$redir.'');
    }
}

function admin($text){
    if($_SESSION['level']=='Admin'){
        return $text;
    }
}

function tanggal($tgl="10 Mei 1993"){
    $val=explode(' ',$tgl);
    $tg=$val['0'];
    $b=$val['1'];
    $th=$t=$val['2'];
    $indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
    for($i=0; $i<=12; $i++){    
        if($b==$indo[$i]){
        $b=$i+1;
        }
    }
    $tgl="$tg/$b/$th";
    return $tgl;
}

function hitung_hari($h='1', $b='1', $t='2012', $h2='1', $b2='1', $t2='2012'){
    $awal=gregoriantojd($b,$h,$t);
    $akhir=gregoriantojd($b2,$h2,$t2);
    $total=$akhir-$awal;
    return $total;
}
/*Fungsi Denda*/
function denda($tempo, $sekarang){
    $sekarang=tanggal($sekarang);
    $tempo=tanggal($tempo);
    //echo $sekarang.$tempo;
    $sekarang=explode('/',$sekarang);
    $tempo=explode('/',$tempo);
    $tg=$sekarang['0'];
    $bl=$sekarang['1'];
    $th=$sekarang['2'];
    //
    $tg2=$tempo['0'];
    $bl2=$tempo['1'];
    $th2=$tempo['2'];
    //
    $denda=hitung_hari($tg2,$bl2,$th2,$tg,$bl,$th);
    //echo $denda;
    if($denda > 15){
        $denda='30000';
    }
    switch($denda){
        case '1':
            $denda='1000';
            break;
        case '2':
            $denda='2000';
            break;
        case '3':
            $denda='3000';
            break;
        case '4':
            $denda='4000';
        case '5':
            $denda='5000';
            break;
        //
        case '6':
            $denda='7000';
            break;
        case '7':
            $denda='9000';
            break;
        case '8':
            $denda='11000';
            break;
        case '9':
            $denda='13000';
            break;
        case '10':
            $denda='15000';
            break;
        //
        case '11':
            $denda='18000';
            break;
        case '12':
            $denda='21000';
            break;
        case '13':
            $denda='24000';
            break;
        case '14':
            $denda='27000';
            break;
        case '15':
            $denda='30000';
            break;
    }
    
    return $denda;

}

/*Export Exel*/
class exel{
    private $h,$u,$p,$t;
    public $d, $k, $db, $sq, $nama="Libska";
    
    /*public function __construct($host,$user,$pwd,$db){
    $this->h=$host;
    $this->u=$user;
    $this->p=$pwd;
    $this->d=$db;
    $this->k=mysql_connect($this->h,$this->u,$this->p);
    $this->db=mysql_select_db($this->d);
    }*/
    public function setNama($nama="Libska"){
        $this->nama=$nama;
    }
    public function doExport($sql){
        $this->sq=$sql;
        $nama_file=$this->nama."-".date('d-m-Y').".xls";
        $conn = $this->k;
        $db = $this->db;
        //$sql = "SELECT * FROM biodata_siswa";
        $rec = mysql_query($sql) or die (mysql_error());
        $num_fields = mysql_num_fields($rec);
   
        for($i = 0; $i < $num_fields; $i++ )
        {
            $header .= mysql_field_name($rec,$i)."\t";
        }
       
        while($row = mysql_fetch_row($rec))
        {
            $line = '';
            foreach($row as $value)
            {                                           
                if((!isset($value)) || ($value == ""))
                {
                    $value = "\t";
                }
                else
                {
                    $value = str_replace( '"' , '""' , $value );
                    $value = '"' . $value . '"' . "\t";
                }
                $line .= $value;
            }
            $data .= trim( $line ) . "\n";
        }
       
        $data = str_replace("\r" , "" , $data);
       
        if ($data == "")
        {
            $data = "\n Datane Ora Enek!\n";                       
        }
        catat($_SESSION['nama'], "Mengunduh File >>> $nama_file");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$nama_file");
        header("Pragma: no-cache");
        header("Expires: 0");
        $copy="Generated by Libska 2012 ShowCheap";
        print "$header\n$data\n$copy";

    }
}
function getVersion(){
    return "3.0.0";
}

function get_sistem($opt){
    $sql=mysql_query("select * from sistem where param='$opt'");
    $q=@mysql_fetch_array($sql);
    
    return $q['value'];
}

function set_sistem($inpt, $val){
    $s=mysql_query("update sistem set value='$val' where param='$inpt'");
    if(!$s){
        echo "<script type='text/javascript'>alert(\"Gagal Menyimpan  Konfigurasi\")</script>";
    }
}
//31 Jul 2012
function get_nama($id){
    $s=mysql_query("select * from siswa where no_induk=\"$id\"");
    $r=mysql_fetch_array($s);
    
    return $r["nama"];
}
?>
