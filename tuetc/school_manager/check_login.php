<?php 
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

$username= str_replace("'", "\'", $username);
$password= sha1($password);
include 'define.php';
$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
mysqli_query($conn, "set names 'utf8'");
$result = mysqli_query($conn, "select * from user where username='" . $username . "' and password='" . $password . "'");
if (mysqli_num_rows($result) == 0) {
    echo 'Bạn nhập sai username hoặc password';
    exit;
} else {
    $_SESSION['username']=$username;
    echo 'ok';
    exit;
}
?>