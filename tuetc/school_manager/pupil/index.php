<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Học sinh</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="../public/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../public/css/menu.css" rel="stylesheet" type="text/css"/>   
        
        <script type="text/javascript">
            jQuery(function($){
                $("img.delete").click(function() {
                    $.ajax({
                       url:'../common/delete.php?id='+$(this).attr('id')+'&table_name=pupil'
                    });
                    $(this).parent().parent().remove();
                });
            });
        </script>
    </head>
    <body>
        <?php 
        include_once  '../menu.php';
        ?>
        <div class="right toolbar">
            <input onclick="window.location = 'add.php';" type="button" value="Thêm mới" class="button">
        </div>
        <table class="list" style="width: 100%;">

            <tr>
                <th style="width: 10%;">
                    lớp
                </th>
                <th style="width: 10%;">
                    họ tên
                </th>
                <th style="width: 10%;">
                    ngày sinh
                </th>
                <th style="width: 10%;">
                    giới tính
                </th>
                <th style="width: 10%;">
                    Tình trạng hôn nhân
                </th>
                <th style="width: 10%;">
                    avatar
                </th>
                <th style="width: 20%;">
                    vài thông tin khác                    
                </th>
                <th style="width: 20%;">&nbsp;</th>
            </tr>
            <?php 
            include '../define.php';
            $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
            mysqli_query($conn, "set names 'utf8'");
            $result = mysqli_query($conn, "select * from pupil_full order by class_id ASC");
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td>
                        <?php
                        echo convertToVNDate($row['birthday']);
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['sex'] == '1') {
                            echo 'nam';
                        } else {
                            echo 'nữ';
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['married'] == '1') {
                            echo 'đã kết hôn';
                        } else {
                            echo 'độc thân';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if (trim($row['avatar']) != '' && file_exists("../public/images/database/avatar/" . trim($row['avatar']))) {
                                ?>
                            <img src="../public/images/database/avatar/<?php echo $row['avatar'];?>" style="width: 50px;height: 50px;"/>
                        <?php 
                        } 
                        ?>
                    </td>
                    <td>
                        <?php echo $row['introduce']; ?>
                        <br>
                        <?php 
                        if (trim($row['profile']) != '' && file_exists("../public/images/database/profile/" . trim($row['profile']))) {
                                ?>
                            <a href="download.php?file_name=<?php echo $row['profile']; ?>">
                                download
                            </a>
                        <?php 
                        } 
                        ?>
                    </td>
                    <td style="text-align: center;">
                        
                        <img id="<?php echo $row['id']; ?>" class="delete" style="margin-right: 20px;" title="Nhấn vào đây để xóa" src="../public/images/delete-icon.png"/>

                        <img onclick="window.location='edit.php?id=<?php echo $row['id']; ?>';" title="Nhấn vào đây để sửa" src="../public/images/ico_edit.png"/>
                        
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>

<?php

function convertToVNDate($dateTime) {
    $temp = explode(' ', $dateTime);
    $dateEn = $temp[0];
    $temp = explode('-', $dateEn);
    $dateVn = $temp[2] . '/' . $temp[1] . '/' . $temp[0];
    return $dateVn;
}
?>