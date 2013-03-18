<?php
class siswa{
    private $induk;
    //dari tabel siswa
    public function setInduk($induk){
        $this->induk=$induk;
    }
    public function getInduk(){
        return $this->induk;
    }
    public function cekAda(){
        $mysql=mysql_query("SELECT * FROM tbl_anggota WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_num_rows($mysql);
        if($r==1){
            return true;
        }else{
            return false;
        }
    }
    public function getNama(){
        $mysql=mysql_query("SELECT nama FROM tbl_anggota WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["nama"];
    }
    public function getKelas(){
        $mysql=mysql_query("SELECT kelas FROM tbl_anggota WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["kelas"];
    }
    public function getJurusan(){
        $mysql=mysql_query("SELECT jurusan FROM tbl_anggota WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["jurusan"];
    }
    public function getMeminjam(){
        $mysql=mysql_query("SELECT count FROM tbl_anggota WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["count"];
    }
    public function getDenda(){
        $mysql=mysql_query("SELECT denda FROM tbl_anggota WHERE no_induk=\"".$this->induk."\"");
        $r=mysql_fetch_array($mysql);
        return $r["denda"];
    }
    //dari data semua
    public function getSedangMeminjam(){
        $induk=$this->getInduk();
        $sql=mysql_query("SELECT * FROM tbl_peminjaman WHERE siswa=\"$induk\" AND kembali=\"0\"");
        return mysql_num_rows($sql);
    }
    public function getTotalMeminjam(){
        $induk=$this->getInduk();
        $sql=mysql_query("SELECT * FROM tbl_peminjaman WHERE siswa=\"$induk\"");
        return mysql_num_rows($sql);
    }
}

?>