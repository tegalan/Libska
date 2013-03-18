<?php
session_start();
/*
File: kas.php
Fungsi: Managemen Uang Kas
Auth: ShowCheap
*/
require 'sistem/config.php';
sambung();
cek_user();
get_kepala();
$kas=new db();

if(isset($_POST['tombol']) && isset($_SESSION['nama'])){
    $val=mysql_real_escape_string($_POST['val']);
    $ket=mysql_real_escape_string($_POST['ket']);
    $type=$_POST['jenis'];
    $skg=date('Y-m-d');
    $sal_akhr=$kas->single("SELECT saldo FROM tbl_kas ORDER by id DESC LIMIT 0,1");
    if($type=='keluar'){
        $saldo=$sal_akhr - $val;
        $cat="Pengeluaran";
    }elseif($type=='masuk'){
        $saldo=$sal_akhr + $val;
        $cat="Pemasukkan";
    }
    catat($_SESSION['nama'], "$cat sejumlah $val");
    mysql_query("INSERT INTO tbl_kas SET $type = '$val', tgl = '$skg', ket='$ket', saldo='$saldo'");
}
?>
<script type='text/javascript'>
    function cek_ok(){
        var valu=document.forms['frm-kas']['jenis'].value;
        if(valu=='pilih'){
            document.forms['frm-kas']['tombol'].disabled=true;
        }else{
            document.forms['frm-kas']['tombol'].disabled=false;
        }
    }
</script>
<script type='text/javascript'>
function uang(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num);
}
</script>
<table name='kas' cellpadding='3' cellspacing='1' width='80%' align='center' class="table table-striped table-hover">
<tr><td></td><td colspan=5'><h2>Kas Perpustakaan</h2></td></tr>
<tr>
    
    <td colspan=6'>
    <form action='' method='post' name='frm-kas' class="input-prepend">
        <span class="add-on">Rp.</span><input id='prependedInput' type='text' name='val' autocomplete='off'> <span class="add-on">Ket</span>
        <input type='text' name='ket' autocomplete='off'>
        <select name='jenis' onchange='cek_ok()' id='sel' style="width: 100px">
            <option value='pilih'>Pilih</option>
            <option value='keluar'>Keluar</option>
            <option value='masuk'>Masuk</option>
        </select>
        <input type='submit' value='OK' disabled='disabled' name='tombol' class="btn btn-success" >
        </form>
    </td>
</tr>
    <tr style='background-color: gray; color: #808080;'>
        <td width='20'>No</td><td width='150'>Tanggal</td><td>Uraian</td><td>Masuk</td><td>Keluar</td><td>Saldo</td>
    </tr>
    <?php
    $hel= new db();
    $bts= 50;
    $jumlah=$hel->baris("SELECT * FROM tbl_kas");
    $jhal=ceil($jumlah/$bts);
    $hal=$jhal-1;
    $mulai= $hal * $bts;
    
    
    $kas->sql("SELECT * FROM tbl_kas LIMIT $mulai, $bts");
    $no=1;
    while($kas->hasil()){
        echo "<tr style='background-color: #E1E1E1;'>";
        echo "<td>$no</td>";
        echo "<td>".$kas->hasil['tgl']."</td>";
        echo "<td>".$kas->hasil['ket']."</td>";
        echo "<td align='right'><script type='text/javascript'>document.write(uang(".$kas->hasil['masuk']."))</script></td>";
        echo "<td align='right'><script type='text/javascript'>document.write(uang(".$kas->hasil['keluar']."))</script></td>";
        echo "<td align='right'><script type='text/javascript'>document.write(uang(".$kas->hasil['saldo']."))</script></td>";
        //echo $kas->hasil['saldo'];
        $no++;
    }
    ?>
<tr style='background-color: gray; color: black;'>
    <td colspan='3' align='center'>Total</td>
    <td align='right'>Rp.<script type='text/javascript'>document.write(uang(<?php echo $mas=@$kas->single("SELECT sum(masuk) FROM tbl_kas"); ?>))</script></td>
    <td align='right'>Rp.<script type='text/javascript'>document.write(uang(<?php echo $kel=@$kas->single("SELECT sum(keluar) FROM tbl_kas"); ?>))</script></td>
    <td align='right'><b>Rp.<script type='text/javascript'>document.write(uang(<?php echo $mas-$kel; ?>))</script></b></td>
</tr>
</table>

<?php echo "<b style='font-size: 8px;'>Hal: $jhal</b>"; get_kaki(); ?>