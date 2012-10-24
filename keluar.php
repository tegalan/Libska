<?php
session_start();
require_once 'sistem/config.php';
sambung();
catat($_SESSION['nama'], "Keluar Aplikasi");
unset($_SESSION['level']);
unset($_SESSION['nama']);
if($_SESSION['level']=='' && $_SESSION['nama']==''){
    header('location:index.php');
}

?>