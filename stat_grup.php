<?php
session_start();
/*
File: stat_grup.php
Fungsi: statistik buku berdasarkan kelompok buku
Auth: ShowCheap
*/
include 'sistem/config.php';

sambung();
get_kepala();
$kepala=array();

        $lb=$_GET["bul"];
        $teh=$_GET["thn"];
        if($lb==''){
            $lb=date('m');
        }
        if($teh==''){
            $teh=date('Y');
        }
//echo $teh;
function kep($kep, $hr, $bul, $thn){
    $q=mysql_query("SELECT * FROM tbl_peminjaman WHERE tgl_pinjam LIKE '%$hr' AND buku LIKE '$kep%' AND tgl_pinjam LIKE '%-$bul-%' AND tgl_pinjam LIKE '$thn%'");
//    echo "SELECT * FROM tbl_peminjaman WHERE tgl_pinjam LIKE '%$hr' AND buku LIKE '$kep%' AND tgl_pinjam LIKE '%-$bul-%' AND tgl_pinjam LIKE '$thn%'";
    $r= mysql_num_rows($q);
    return $r;
}

$indo=array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember');
$jum=count($indo)-1;
?>
<p style='font-size: 23px;'>Statistik Peminjaman Berdasar Kelompok Buku</p>
<form action='' method='get' name='bulan'>
    <select name='bul' onchange='document.forms["bulan"].submit();'>
        <?php
            
            for($bb=0; $bb<=$jum; $bb++){
                $pilih=null;
                    if($lb==$bb+1){
                        $pilih="selected=selected";
                    }
                echo "<option $pilih value='".($bb+1)."'>".$indo[$bb]."</option>";
        }  
        ?>
    </select>
    <select name='thn' onchange='document.forms["bulan"].submit();'>
        <?php
            $now=date('Y');
            
            for($th=2010; $th<=$now; $th++){
                $sel=null;
                if($th==$teh){
                    $sel="selected=selected";
                }
                echo "<option $sel>$th</option>";
            }
        
        ?>
    </select>
</form>
Menampilkan Data Bulan <b><?php echo $indo[$lb-1]." ".$teh; ?></b>

<table width='100%' border='1' cellspacing='0'>
    <tr style='background-color: #3366ff; color: #703C3C; font-weight: bold;'>
        
        <td align='center'>Tanggal</td>
        <?php
        for($i=0; $i<=9; $i++){
            echo "<td align='center'>".$i."00 - ".$i."99</td>";
        }
        ?>
        <td align='center'>Jumlah</td>
    </tr>
    <?php
        
        $row=array();
        $tot=array(0,0,0,0,0,0,0,0,0,0,0);
        $jumlah_hari=cal_days_in_month(CAL_GREGORIAN, $lb, date('Y'));
        for($k=1; $k<=$jumlah_hari; $k++){
            $ka=sprintf("%02s", $k);
            //echo $ka."<br>";
            $lb=sprintf("%02s", $lb);
            ?>
            
            
            <tr class='brum'>
                
                <td align='center' style='background-color: #EF9D30'><?php echo "$ka ".$indo[$lb-1]; ?></td>
                <td align='center' id='ganjil'><?php echo $row[0]=kep(0,$ka,$lb,$teh); $tot[0]=$tot[0]+$row[0] ?></td>
                <td align='center' id='genap'><?php echo $row[1]=kep(1,$ka,$lb,$teh); $tot[1]=$tot[1]+$row[1] ?></td>
                <td align='center' id='ganjil'><?php echo $row[2]=kep(2,$ka,$lb,$teh); $tot[2]=$tot[2]+$row[2]  ?></td>
                <td align='center' id='genap'><?php echo $row[3]=kep(3,$ka,$lb,$teh);  $tot[3]=$tot[3]+$row[3] ?></td>
                <td align='center' id='ganjil'><?php echo $row[4]=kep(4,$ka,$lb,$teh); $tot[4]=$tot[4]+$row[4]  ?></td>
                <td align='center' id='genap'><?php echo $row[5]=kep(5,$ka,$lb,$teh); $tot[5]=$tot[5]+$row[5]  ?></td>
                <td align='center' id='ganjil'><?php echo $row[6]=kep(6,$ka,$lb,$teh); $tot[6]=$tot[6]+$row[6]  ?></td>
                <td align='center' id='genap'><?php echo $row[7]=kep(7,$ka,$lb,$teh);  $tot[7]=$tot[7]+$row[7] ?></td>
                <td align='center' id='ganjil'><?php echo $row[8]=kep(8,$ka,$lb,$teh); $tot[8]=$tot[8]+$row[8]  ?></td>
                <td align='center' id='genap'><?php echo $row[9]=kep(9,$ka,$lb,$teh); $tot[9]=$tot[9]+$row[9]  ?></td>
                <td align='center' id='ganjil'><?php echo $jum=array_sum($row); $tot[10]=$tot[10]+$jum ?></td>
            </tr>
        <?php };
    ?>
    <tr>
        <td align='center' style='background-color: #EF9D30'>Total</td>
        <?php
        for($lo=0;$lo<=10;$lo++){
            echo "<td align='center' style='background-color: #ffffcc'><b>".$tot[$lo]."</b></td>";
        }
        
        ?>
        
    </tr>
</table>

<?php
//echo kep(0,"Mei");
get_kaki();


?>