<?php
class siswa{
    private $induk;
    public function setInduk($induk){
        $this->induk=$induk;
    }
    public function getInduk(){
        return $this->induk;
    }
    public function cekAda(){
        $mysql=mysql_query("SELECT * FROM siswa WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_num_rows($mysql);
        if($r==1){
            return true;
        }else{
            return false;
        }
    }
    public function getNama(){
        $mysql=mysql_query("SELECT nama FROM siswa WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["nama"];
    }
    public function getKelas(){
        $mysql=mysql_query("SELECT kelas FROM siswa WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["kelas"];
    }
    public function getJurusan(){
        $mysql=mysql_query("SELECT jurusan FROM siswa WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["jurusan"];
    }
    public function getMeminjam(){
        $mysql=mysql_query("SELECT count_pinjam FROM siswa WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["count_pinjam"];
    }
    public function getDenda(){
        $mysql=mysql_query("SELECT denda FROM siswa WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["denda"];
    }
}

?>