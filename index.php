<?php
session_start();
/*
File: index.php
Fungsi: Resepsionis :D
Auth: ShowCheap
*/

//Chek Upgrade Dir
if(file_exists("upgrade/index.php") && $_GET['upgrade']!='selesai'){
    header('location: upgrade/index.php');
    exit(0);
}elseif($_GET['upgrade']=='selesai'){
    if(rename("upgrade","upgrade_finish")){
        
    }else{
        echo "Failed remove Dir upgrade";
    }
}
include 'sistem/config.php';
//include 'run.php';
sambung();
get_kepala();

if(isset($_GET['stat'])){
    include 'halaman/stat.php';
    get_kaki();
    exit();
}

?>
<script type='text/javascript'>
    $(document).ready(function(){
        $(".tempo").click(function(){
            $.ajax({
                url:'run.php',
                success: function(data){
                    $("#res").html(data);
                }
            })
        });
      });    
</script>

<div class="tabbable" style="background: white; border-radius:6px; padding: 2px;"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Welcome</a></li>
    <li class="aktif"><a href="#tab2" data-toggle="tab">Peminjam Aktif</a></li>
    <li class="populer"><a href="#tab3" data-toggle="tab">Buku Populer</a></li>
    <li class="tempo"><a id="tempo" href="#tab4" data-toggle="tab">Buku Jatuh Tempo</a></li>
  </ul>
  <div class="tab-content" style="padding: 5px;">
    <div class="tab-pane active" id="tab1">
      <!--Welcome-->
      <div class="hero-unit">
        <h1>Selamat Datang <?php $nm=explode(" ",$_SESSION['nama']); echo $nm[0];  ?> !</h1>
        <p>Selamat datang di program administrasi perpustakaan Libska Versi <?php echo getVersion(); ?></p>
        <p>
          <a class="btn btn-primary btn-large" href="buku.php">
            Lanjutkan 
          </a>
        </p>
      </div>
    </div>
    <div class="tab-pane" id="tab2">
    <!--peminjam aktif-->
     <table class="table table-striped table-hover" width='100%' cellpadding='2' cellspacing='1' style='font-size: 12px; border: 1px inset;'><!--Tabel anak2--> 
        <thead>       
            <tr align='center' style="font-weight: bold;">
                <td>No</td><td>Nama</td><td>Kelas</td><td>Jurusan</td><td>Peminjaman</td>
            </tr>
        </thead> 
        <?php $i='1'; $ab=mysql_query("SELECT * FROM tbl_anggota WHERE count !='0' AND kelas NOT LIKE 'Alumni%' ORDER BY count DESC limit 0,50"); while($cde=mysql_fetch_array($ab)){ ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><a title="Klik Untuk Melihat" href="peminjaman.php?pencarian=<?php echo $cde['no_induk']; ?>"><?php echo $cde['nama']; ?></a></td>
                <td><?php echo $cde['kelas']; ?> </td>
                <td><?php echo $cde['jurusan']; ?> </td>      
                <td><?php echo $cde['count']; ?> Kali</td>
            </tr>            
         <?php $i++; } ?>
      </table><!--Tabel anak2--> 
    </div>
    <div class="tab-pane" id="tab3">
    <!--buku populer-->
       <table  class="table table-striped table-hover" width='100%' cellpadding='2' cellspacing='1' border='0' style='border: 1px inset;' id='popl'><!--Tabel anak-->
        <thead>
            <tr align='center' style="font-weight: bold;">
                <td>No</td><td>Judul Buku</td><td>Status</td><td>Di Pinjam</td>
            </tr>
        </thead>
        <?php $popu=mysql_query("SELECT * FROM tbl_buku WHERE count != '0' ORDER BY count DESC LIMIT 0,50"); $no='1'; while($pop=mysql_fetch_array($popu)){ ?>
            <tr id='pop'>
                <td><?php echo $no; ?></td>
                <td><?php echo $pop['judul']; ?></td>
                <td><?php echo $pop['status']==1?"<span class='label label-success'>Tersedia</span>":"<span class='label label-important'>Dipinjam</span>"; ?></td>
                <td><?php echo $pop['count']; ?> Kali</td>
            </tr>
            <?php $no++; } ?>   
        </table><!--Tabel anak-->
    </div>
    <div class="tab-pane" id="tab4">
    <!--jatuh tempo-->
        <table  class="table table-striped table-hover" width='100%' style='font-size: 12px; border: 1px inset;' cellspacing='1' cellpadding='2'>
          <thead>
            <tr align='center' style="font-weight: bold;">
              <td>No</td><td>Kode Buku</td><td>Judul Buku</td><td>Peminjam</td><td width="100px">Tanggal</td>
            </tr>
          </thead>
          <?php
          $jatuh=new db();
          $saiki=sekarang();
          $jatuh->sql("SELECT * FROM tbl_telat");
          $jum=$jatuh->getJml();
          if($jum !='0'){
              $noer=1;
              while($jatuh->hasil()){
                  echo "<tr id='pop' title='Kode Buku ".$jatuh->hasil['buku']."'>";
                  echo "<td>$noer</td>";
                  echo "<td>".$jatuh->hasil['buku']."</td>";
                  echo "<td>".$jatuh->hasil['judul']."</td>";
                  echo "<td><a href='peminjaman.php?pencarian=".$jatuh->hasil['induk']."'>".$jatuh->hasil['siswa']."</a></td>";
                  echo "<td>".date('d M Y', strtotime($jatuh->hasil['tanggal']))."</td>";
                  echo "</tr>";
                  $noer++;
              }
          }else{
              echo "<tr id='pop' align='center'><td colspan='5' id='res'><a class='btn' href='#' id='tempo'>Tidak Ada</a></td></tr>";
              echo "<td colspan='5'><div style=\"background-color: #ebf2e6;\" id='reds'></div></tr>";
          }
          ?>
          <tr>
              <td colspan='5'><div style="background-color: #ebf2e6;" id='res'></div></tr>
          </table>
    </div>
  </div>
</div>
<?
get_kaki();
?>