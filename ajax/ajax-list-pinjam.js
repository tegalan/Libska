function kembali(x,y,z,b){
    
    $.ajax({
        url:'ajax/ajax-list-pinjam.php?kembali=satu&tgl_pinjam='+y+'&siswa='+z+'&buku='+b+'',
        type:'GET',
        success:function(data){
            //$("#row-"+x).html(data);
            $("#row-"+x).html('<td colspan="5" class="alert alert-success" style="text-align:center;">Buku Sudah di kembalikan</td>');
            $("#row-"+x).slideUp(5000);
        }
    })
    
}
function goAll(siswa){
    //alert('ajax/ajax-list-pinjam.php?kembali=all&siswa='+siswa);
    $.ajax({
        url:'ajax/ajax-list-pinjam.php?kembali=all&siswa='+siswa,
        type:'GET',
        success:function(data){
            //$("#tbody").html("<td colspan='5'>"+data+"</td>");
            $(".item").slideUp('slow');
            $("#tbody").html("<td colspan='5' class='alert alert-success' style='text-align:center;'>Semua buku sudah di kembalikan</td>");
        }
    })
}