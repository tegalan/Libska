<?php
/*
File: kaki.php
Fungsi: Mengatur Tampilan bagian bawah
Auth: ShowCheap
*/

?>
<script type='text/javascript'>
    function about(){
        alert("Libska Version <?php echo get_sistem("versi"); ?>\nPerpustakaan <?php echo get_sistem("Nama"); ?>\n\nProgram By: Sucipto\nDedicated To: SMKN 1 Ngawi\nVisit: www.sucipto.net\n\nCopyright 2012 Alright Reserved")
    }
</script>
</div>
<div id='copyright' onclick='about()' title='Tentang Aplikasi Ini (Click Here!!)'>Copyright &copy; <?php echo date('Y'); ?> Sucipto | SkanSoft &trade;</div>
</body>
</html>