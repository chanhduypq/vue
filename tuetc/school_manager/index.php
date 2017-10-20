<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Trang chủ</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="public/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="public/css/menu.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php 
        include_once  'menu.php';
        ?>
        
        <div class="home">
            Chào mừng bạn đến với hệ thống quản lý trường học
        </div>
        
    </body>
</html>