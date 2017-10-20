<?php
$pupils = getPupilsByClassId($_GET['class_id']);
if (count($pupils) > 0) {
    ?>
    <ul>
        <?php
        for ($i = 0; $i < count($pupils); $i++) {
            echo '<li>' . $pupils[$i]['full_name'] . '</li>';
        }
        ?>
    </ul>
    <?php
}
else{
    echo '';
}
exit;

function getPupilsByClassId($classId) {
    $pupils = array();
    include 'define.php';
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
    mysqli_query($conn, "set names 'utf8'");
    $result = mysqli_query($conn, "SELECT * from pupil WHERE class_id=$classId");
    while ($row = mysqli_fetch_array($result)) {
        $pupils[] = $row;
    }
    return $pupils;
}
?>




