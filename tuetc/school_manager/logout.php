<?php 
//hủy session
session_start();
session_destroy();
//quay lại trang login.php
header('Location:login.php');
?>

