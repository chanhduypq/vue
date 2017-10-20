<?php 
session_start();
if(isset($_SESSION['username'])){
    header('Location:index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login</title> 
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="public/css/style.css" rel="stylesheet" type="text/css"/>       
        <script src="public/js/jquery-2.0.3.js"></script>
        <script src="http://localhost/vue/dist/vue.min.js"></script>
    </head>
    <body>
        <form id='frm_login' action="check_login.php" method="post" @submit.prevent="login">
            
            <div style="width: 100%;text-align: center;margin: 0 auto;padding-left: 30%;padding-right: 30%;">
                <fieldset style="width: 40%;">
                    <legend align="center">đăng nhập</legend>
                    <label>
                        username:
                        <input id="username" name="username" type="text" placeholder="nhập username vào đây"/>
                    </label>
                    <br>
                    <label>
                        password:
                        <input id="password" name="password" type="password" placeholder="nhập password vào đây"/>
                    </label>
                    <br>
                    <div class="center">
                        <input id='submit' type="submit" value="login"/>    
                    </div>                
                </fieldset>
            </div>

        </form>
        <script type="text/javascript">
            
            function validate(){

                if($('#username').val()==''){
                    alert("Vui lòng nhập username");
                    $('#username').focus();
                    return false;
                }
                if($('#password').val()==''){
                    alert("Vui lòng nhập password");
                    $('#password').focus();
                    return false;
                }
                return true;
            }
            
            var form = new Vue({
              el: '#frm_login',
              methods: {
                login: function () {                    
                    if(validate()==true){
                        $.ajax({
                           url:'check_login.php',
                           type: 'POST',
                           data: $("#frm_login").serialize(),
                           success: function(data) {
                               if($.trim(data)=='ok'){
                                   window.location='index.php';
                               }
                               else{
                                   alert(data);
                               }
                          }
                           
                       });
                    }
                }
              }
            });  
            

        </script>
    </body>
</html>   

