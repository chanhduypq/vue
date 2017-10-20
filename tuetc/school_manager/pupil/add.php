<?php 
include '../define.php';
$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
mysqli_query($conn, "set names 'utf8'");

/**
 * đây là đoạn code xử lý khi user vừa submit
 * lưu vào database, sau đó quay lại trang index
 */
if (count($_POST) > 0) {
    insert($conn);
    header('Location:index.php');
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
        
        
        <link rel="stylesheet" href="../public/jquery-ui-1.10.3/themes/smoothness/jquery-ui.css" type="text/css"/>
        <script type="text/javascript" src="../public/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
        <script src="../public/jquery-ui-1.10.3/ui/i18n/jquery.ui.datepicker-vi.js"></script>
    </head>
    <body>
        <?php 
        include_once  '../menu.php';
        ?>
        <div class="right toolbar">
            <input onclick="window.location='index.php';" type="button" value="Quay lại" class="button">
        </div>
        <form action="add.php" method="post" onsubmit="return validate();" enctype="multipart/form-data">
            <table width="40%"> 
                <tbody>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label for="class_id">Lớp:<span style="color:red;"> *</span></label>
                        </td>
                        <td nowrap="nowrap" style="width: 20%;">
                            <select name="class_id" id="class_id">
                                <option value="">----Chọn lớp----</option>
                                <?php
                                $result = mysqli_query($conn, "select * from class");
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>					                 
                        </td>

                    </tr>     

                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label for="full_name">Họ và tên:<span style="color:red;"> *</span></label>
                        </td>
                        <td nowrap="nowrap">
                            <input type="text" name="full_name" id="full_name">					                
                        </td>

                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label for="birthday">Ngày sinh:<span style="color:red;"> *</span></label>
                        </td>
                        <td nowrap="nowrap">
                            <input style="background-color: #eeeeee;cursor: not-allowed;" readonly="readonly" type="text" name="birthday" id="birthday">
                        </td>
                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label>Giới tính:<span style="color:red;"> *</span></label>
                        </td>
                        <td nowrap="nowrap">
                            <label><input type="radio" name="sex" value="1" checked="checked"/>Nam</label>
                            <label><input type="radio" name="sex" value="0"/>Nữ</label>
                        </td>

                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            &nbsp;
                        </td>
                        <td nowrap="nowrap">
                            <label><input type="checkbox" name="married" value="1"/>Đã kết hôn</label>
                        </td>

                    </tr>
                    
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label>Ảnh đại diện:</label>
                        </td>
                        <td nowrap="nowrap">
                            <input type="file" name="avatar"/>
                        </td>

                    </tr>
                    
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label for="introduce">Vài thông tin khác:</label>
                        </td>
                        <td nowrap="nowrap">
                            <textarea id="introduce" name="introduce" cols="50" rows="5"></textarea>
                        </td>

                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            &nbsp;
                        </td>
                        <td nowrap="nowrap">
                            <input type="file" name="profile"/>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2" align="center" style="width: 40%;padding-top: 30px;">
                            <input type="submit" value="Thêm mới"/>
                        </td>
                    </tr>

                </tbody>
            </table>
        </form>

        <script type="text/javascript">

            function validate() {

                if ($('#class_id').val() == '') {
                    alert("Vui lòng chọn lớp");
                    $('#class_id').focus();
                    return false;
                }
                full_name = $('#full_name').val();
                if ($.trim(full_name) == '') {
                    alert("Vui lòng nhập họ và tên");
                    $('#full_name').focus();
                    return false;
                }
                birthday = $('#birthday').val();
                if ($.trim(birthday) == '') {
                    alert("Vui lòng nhập ngày sinh");
                    $('#birthday').focus();
                    return false;
                }
                return true;
            }
            
            jQuery(function ($){
               $("#class_id").focus(); 
               
               $( "#birthday" ).datepicker({
                  changeMonth: true,
                  changeYear: true,
                  dateFormat: "dd/mm/yy",
                  showWeek: true,
                    showOn: "button",
                    buttonImage: "../public/images/calendar.gif",
                    buttonImageOnly: true,
                    buttonText: 'Click để chọn ngày',
                    option:$.datepicker.regional['vi']       

                });

                $('img.ui-datepicker-trigger').css('margin-left','10px').css('cursor','pointer');
            });
        </script>
    </body>
</html>
<?php

function convertToENDate($dateVn) {
    $temp = explode('/', $dateVn);
    $dateEn = $temp[2] . '/' . $temp[1] . '/' . $temp[0];
    return $dateEn;
}

function insert($conn){
    $class_id = $_POST['class_id'];
    $full_name = $_POST['full_name'];
    $full_name = str_replace("'", "\'", $full_name);
    $full_name= htmlentities($full_name);
    $birthday = $_POST['birthday'];
    $birthday = convertToENDate($birthday);
    
    if (isset($_POST['married']) && $_POST['married'] == '1') {
        $married = '1';
    } else {
        $married = '0';
    }
    
    $introduce=$_POST['introduce'];
    $introduce = str_replace("'", "\'", $introduce);
    $introduce= htmlentities($introduce);
    
    if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) && $_FILES['avatar']['name'] != '') {
        $avatar = $_FILES['avatar']['name'];
        $extension = explode(".", $avatar);
        $extension = $extension[count($extension) - 1];
        $avatar = sprintf('_%s.' . $extension, uniqid(md5(time()), true));
        move_uploaded_file($_FILES['avatar']['tmp_name'], "../public/images/database/avatar/" . $avatar);
    }
    else{
        $avatar='';
    }
    
    if (isset($_FILES['profile']) && isset($_FILES['profile']['name']) && $_FILES['profile']['name'] != '') {
        $profile = $_FILES['profile']['name'];
        $extension = explode(".", $profile);
        $extension = $extension[count($extension) - 1];
        $profile = sprintf('_%s.' . $extension, uniqid(md5(time()), true));
        move_uploaded_file($_FILES['profile']['tmp_name'], "../public/images/database/profile/" . $profile);

        
    } else {
        $profile = '';
    }
    
    
    $sex = $_POST['sex'];
    $sql = "insert into pupil "
            . "("
            . "class_id,"
            . "full_name,"
            . "birthday,"
            . "sex,"
            . "introduce,"
            . "married,"
            . "avatar,"
            . "profile"
            . ") "
            . "values "
            . "("
            . "" . $class_id . ","
            . "'" . $full_name . "',"
            . "'" . $birthday . "',"
            . "" . $sex . ","
            . "'" . $introduce . "',"
            . "" . $married . ","
            . "'" . $avatar . "',"
            . "'" . $profile . "'".
            ")";
    mysqli_query($conn, $sql);
    
}
?>