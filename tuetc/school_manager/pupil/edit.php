<?php 
include '../define.php';
$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
mysqli_query($conn, "set names 'utf8'");
/**
 * đây là đoạn code xử lý khi user vừa submit
 * lưu vào database, sau đó quay lại trang index
 */
if (count($_POST) > 0) {
    update($conn);
    header('Location:index.php');
    exit;
}

$id = $_GET['id'];
if (!ctype_digit($id)) {
    header('Location:index.php');
    exit;
}
$sql = "select * from pupil where id=" . $id;
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    $class_id = $row['class_id'];
    $full_name = $row['full_name'];
    $birthday = convertToVNDate($row['birthday']);
    $sex = $row['sex'];
    $married = $row['married'];
    $introduce = $row['introduce'];
    $avatar = $row['avatar'];
    $profile = $row['profile'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Học sinh</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="../public/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../public/css/menu.css" rel="stylesheet" type="text/css"/>        
        <script src="../public/js/jquery-2.0.3.js"></script>

        <link rel="stylesheet" href="../public/jquery-ui-1.10.3/themes/smoothness/jquery-ui.css" type="text/css"/>
        <script type="text/javascript" src="../public/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
        <script src="../public/jquery-ui-1.10.3/ui/i18n/jquery.ui.datepicker-vi.js"></script>
    </head>
    <body>
        <?php
        include_once '../menu.php';
        ?>
        <div class="right toolbar">
            <input onclick="window.location = 'index.php';" type="button" value="Quay lại" class="button">
        </div>
        <form action="edit.php" method="post" onsubmit="return validate();" enctype="multipart/form-data">
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
                                    if ($row['id'] == $class_id) {
                                        $select = ' selected="selected"';
                                    } else {
                                        $select = '';
                                    }
                                    ?>
                                    <option<?php echo $select; ?> value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
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
                            <input type="text" name="full_name" id="full_name" value="<?php echo $full_name; ?>">					                
                        </td>

                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label for="birthday">Ngày sinh:<span style="color:red;"> *</span></label>
                        </td>
                        <td nowrap="nowrap">
                            <input style="background-color: #eeeeee;cursor: not-allowed;" readonly="readonly" type="text" name="birthday" id="birthday" value="<?php echo $birthday; ?>">
                        </td>
                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label>Giới tính:<span style="color:red;"> *</span></label>
                        </td>
                        <td nowrap="nowrap">
                            <label><input type="radio" name="sex" value="1"<?php if ($sex == '1') echo ' checked="checked"'; ?>/>Nam</label>
                            <label><input type="radio" name="sex" value="0"<?php if ($sex == '0') echo ' checked="checked"'; ?>/>Nữ</label>
                        </td>

                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            &nbsp;
                        </td>
                        <td nowrap="nowrap">
                            <label><input type="checkbox" name="married" value="1"<?php if ($married == '1') echo ' checked="checked"'; ?>/>Đã kết hôn</label>
                        </td>

                    </tr>

                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label>Ảnh đại diện:</label>
                        </td>
                        <td nowrap="nowrap">
                            <?php 
                                if (trim($avatar) != '' && file_exists("../public/images/database/avatar/" . trim($avatar))) {
                                ?>
                                <img src="../public/images/database/avatar/<?php echo $avatar; ?>" style="width: 50px;height: 50px;"/>
                                <br>
                                <?php
                            }
                            ?>
                            <input type="file" name="avatar"/>
                        </td>

                    </tr>

                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            <label for="introduce">Vài thông tin khác:</label>
                        </td>
                        <td nowrap="nowrap">
                            <textarea id="introduce" name="introduce" cols="50" rows="5"><?php echo trim($introduce); ?></textarea>
                        </td>

                    </tr>
                    <tr>                 
                        <td nowrap="nowrap" style="width: 20%;text-align: right;">
                            &nbsp;
                        </td>
                        <td nowrap="nowrap">
                            <?php 
                                if (trim($profile) != '') {
                                ?>
                                <a href="download.php?file_name=<?php echo $profile; ?>">
                                    download
                                </a>
                                <br>
                                <?php
                            }
                            ?>
                            <input type="file" name="profile"/>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2" align="center" style="width: 40%;padding-top: 30px;">
                            <input type="submit" value="Sửa"/>
                        </td>
                    </tr>

                </tbody>
            </table>

            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="hidden" name="avatar_filename" value="<?php echo trim($avatar); ?>"/>
            <input type="hidden" name="profile_filename" value="<?php echo trim($profile); ?>"/>
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

            jQuery(function ($) {
                $("#class_id").focus();

                $("#birthday").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd/mm/yy",
                    showWeek: true,
                    showOn: "button",
                    buttonImage: "../public/images/calendar.gif",
                    buttonImageOnly: true,
                    buttonText: 'Click để chọn ngày',
                    option: $.datepicker.regional['vi']

                });

                $('img.ui-datepicker-trigger').css('margin-left', '10px').css('cursor', 'pointer');
            });
        </script>
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

function convertToENDate($dateVn) {
    $temp = explode('/', $dateVn);
    $dateEn = $temp[2] . '-' . $temp[1] . '-' . $temp[0];
    return $dateEn;
}

function update($conn) {
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

    $introduce = $_POST['introduce'];
    $introduce = str_replace("'", "\'", $introduce);
    $introduce= htmlentities($introduce);

    if (isset($_FILES['avatar']) && isset($_FILES['avatar']['name']) && $_FILES['avatar']['name'] != '') {
        $avatar = $_FILES['avatar']['name'];
        $extension = explode(".", $avatar);
        $extension = $extension[count($extension) - 1];
        $avatar = sprintf('_%s.' . $extension, uniqid(md5(time()), true));
        move_uploaded_file($_FILES['avatar']['tmp_name'], "../public/images/database/avatar/" . $avatar);

        $stringAvatarInSql = "avatar='" . $avatar . "',";
        @unlink("../public/images/database/avatar/".$_POST['avatar_filename']);
    } else {
        $stringAvatarInSql = '';
    }
    
    if (isset($_FILES['profile']) && isset($_FILES['profile']['name']) && $_FILES['profile']['name'] != '') {
        $profile = $_FILES['profile']['name'];
        $extension = explode(".", $profile);
        $extension = $extension[count($extension) - 1];
        $profile = sprintf('_%s.' . $extension, uniqid(md5(time()), true));
        move_uploaded_file($_FILES['profile']['tmp_name'], "../public/images/database/profile/" . $profile);

        $stringProfileInSql = "profile='" . $profile . "',";
        @unlink("../public/images/database/profile/".$_POST['profile_filename']);
    } else {
        $stringProfileInSql = '';
    }

    $sex = $_POST['sex'];
    $id = $_POST['id'];
    if (!ctype_digit($id) || !ctype_digit($class_id)) {
        header('Location:index.php');
        exit;
    }
    $sql = "update pupil set " .
            "class_id=" . $class_id . ","
            . "full_name='" . $full_name . "',"
            . "birthday='" . $birthday . "',"
            . "introduce='" . $introduce . "',"
            . $stringAvatarInSql
            . $stringProfileInSql
            . "married=" . $married . ","
            . "sex=" . $sex . " "
            . "where id=" . $id;
    mysqli_query($conn, $sql);
}

?>