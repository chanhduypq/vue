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
        <title>Lớp học</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="../public/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../public/css/menu.css" rel="stylesheet" type="text/css"/> 
        
        <script type="text/javascript">
            jQuery(function($){
                $("img.delete").click(function() {
                    $.ajax({
                       url:'../common/delete.php?id='+$(this).attr('id')+'&table_name=class'
                    });
                    $(this).parent().parent().remove();
                });
                
                $("img.toggle").click(function () {
                    if ($(this).attr('src').indexOf('down') != -1) {
                        src = $(this).attr('src');
                        src = src.replace('down', 'up');
                    } else {
                        src = src.replace('up', 'down');
                    }

                    $(this).attr('src', src);
                    
                    node=$(this);
                    $.ajax({
                       url:'../load_pupil.php?class_id='+$(this).attr('id'),
                       success: function(data) {
                           $(node).parent().parent().find('div.list_pupil').html(data).slideToggle();
                      }

                   });
                    
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
        
        <table class="list" style="width: 60%">
            <tr>
                <th style="width: 40%;">
                    tên lớp
                </th>
                <th style="width: 20%;">&nbsp;</th>
            </tr>
            <?php 
            include '../define.php';
            $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
            mysqli_query($conn, "set names 'utf8'");
            $result = mysqli_query($conn, "SELECT name,id,(SELECT count(*) from pupil WHERE class_id=class.id) as count_pupil FROM class");
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td>
                        <?php 
                        if($row['count_pupil']>0){
                            echo '<div style="float:left;width: 50%;">'.$row['name'].' ('.$row['count_pupil'].' học sinh)</div>';
                        ?> 
                            <div style="float: right;width: 20%;" title="nhấn vào đây để xem danh sách học sinh">
                                <img id="<?php echo $row['id']; ?>" class="toggle" src="../public/images/down.png" style="width: 32px;height: 32px;cursor: pointer;"/>
                            </div>
                            <div style="clear: both;"></div>
                            <div class="list_pupil" style="display: none;background-color: blue;color: white;width: 80%;"></div>
                        <?php 
                        }
                        else{
                            echo '<div style="width: 50%;">'.$row['name'].' (chưa có học sinh nào)</div>'; 
                        }
                        ?>
                            
                    </td>
                    <td style="text-align: center;">
                        <?php 
                        if ($row['count_pupil'] == 0) {
                                ?>                            
                            <img id="<?php echo $row['id']; ?>" class="delete" style="margin-right: 20px;" title="Nhấn vào đây để xóa" src="../public/images/delete-icon.png"/>
                           
                        <?php 
                        }
                        ?>                       
                        
                            <img onclick="window.location='edit.php?id=<?php echo $row['id']; ?>';" title="Nhấn vào đây để sửa" src="../public/images/ico_edit.png"/>
                        
                    </td>
                </tr>
                
                
                    <?php
            }
            ?>
        </table>
    </body>
</html>