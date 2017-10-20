<?php
include '../define.php';
$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
mysqli_query($conn, "set names 'utf8'");
$id = $_GET['id'];
$tableName = $_GET['table_name'];
if (!ctype_digit($id)) {
    exit;
}


if ($tableName == 'pupil') {
    deleteAvatarProfile($conn, $id);
}

$sql = "delete from $tableName where id=$id";
mysqli_query($conn, $sql);

function deleteAvatarProfile($conn, $id) {
    $sql = "select * from pupil where id=$id";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        @unlink("../public/images/database/avatar/" . $row['avatar']);
        @unlink("../public/images/database/profile/" . $row['profile']);
    }
}

?>