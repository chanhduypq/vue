<div class="right">
    <input onclick="window.location='http://localhost/school_manager/logout.php';" type="button" value="Logout" class="logout">    
</div>
<div style="clear: both;"></div>
<ul id="topnav">
    <li<?php if ($_SERVER['SCRIPT_NAME'] == '/school_manager/index.php') echo ' class="active"'; ?>>
        <a href="http://localhost/school_manager/index.php">Trang chủ</a>
    </li>
    <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'class') !== FALSE) echo ' class="active"'; ?>>
        <a href="http://localhost/school_manager/class/index.php">Quản lý lớp</a>
    </li>
    <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'pupil') !== FALSE) echo ' class="active"'; ?>>
        <a href="http://localhost/school_manager/pupil/index.php">Quản lý học sinh</a>
    </li> 
    <li<?php if (strpos($_SERVER['SCRIPT_NAME'], 'guide') !== FALSE) echo ' class="active"'; ?>>
        <a href="http://localhost/school_manager/guide/index.php">Hướng dẫn sử dụng hệ thống</a>
    </li>   
</ul>
<div style="clear: both;"></div>