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
        <script src="public/js/axios.js"></script>
        <script src="http://localhost/vue/dist/vue.min.js"></script>
    </head>
    <body>
        <form id='frm_login' action="check_login.php" method="post" @submit.prevent="login">
            
            <div style="width: 100%;text-align: center;margin: 0 auto;padding-left: 10%;padding-right: 10%;">
                <fieldset style="width: 80%;">
                    <legend align="center">đăng nhập</legend>
                    <label>
                        username:
                        <input v-model="username" id="username" name="username" type="text" placeholder="nhập username vào đây"/>
                    </label>
                    <label v-if="error_username">
                        username không được rỗng
                    </label>
                    <br>
                    <label>
                        password:
                        <input v-model="password" id="password" name="password" type="password" placeholder="nhập password vào đây"/>
                    </label>
                    <label v-if="error_password">
                        password không được rỗng
                    </label>
                    <br>
                    <div class="center">
                        <input id='submit' type="submit" value="login"/>    
                    </div>                
                </fieldset>
            </div>

        </form>
        <script type="text/javascript">
            var form = new Vue({
              el: '#frm_login',
              data: {
                username: '',
                password: ''
            },
            computed:{
                error_username:function (){
                    if(this.username.trim()==''){
                        return true;
                    }
                    return false;
                },
                error_password:function (){
                    if(this.password==''){
                        return true;
                    }
                    return false;
                }
            },
              methods: {
                login: function () {    
                    if(this.error_username==false&&this.error_password==false){
                        axios.post('check_login.php', { username: document.getElementById('username').value, password: document.getElementById('password').value })
                          .then(function(response){
                               if(response.data=='ok'){
                                   window.location='index.php';
                               }
                               else{
                                   alert(response.data);
                               }
                          });  
                    }
                    
                    
                }
              }
            });  
            

        </script>
    </body>
</html>   

