<?php              
require_once '../config.php'; 
session_start();

function login () {
  if (empty($_POST['email'])) {
    $GLOBALS['message'] = '请填写邮箱';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['message'] = '请填写密码';
    return;
  }
  $email = $_POST['email'];                   
  $password = $_POST['password'];
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$conn) {                               //为null
    exit('<h1>连接数据库失败</h1>');
  }
  $query = mysqli_query($conn, "select * from users where email = '{$email}' limit 1; ");
  if (!$query) {
    $GLOBALS['message'] = '登录失败，请重试';
    return;
  }
  $user = mysqli_fetch_assoc($query);
  if (!$user) {
    $GLOBALS['message'] = '邮箱和密码不匹配';
    return;
  } 
  if ($user['password'] !== md5($password)){
    // 密码不正确
    $GLOBALS['message'] = '邮箱与密码不匹配';
    return;
  }
  $_SESSION['current_login_user'] = $user; 
  header('Location: /admin/');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  login();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
  <!-- 想要它左右晃动，加个库函数 -->
</head>
<body>
  <div class="login">
    <form class="login-wrap<?php echo isset($message)?' shake animated':''?>" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" novalidate autocomplete="off">   
      <img class="avatar" src="/static/assets/img/default.png">
      <?php if (isset($message)): ?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $message; ?>
      </div>
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo empty($_POST['email'])? '':$_POST['email']?>"> 
      </div>
      <div class="form-group">   
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>  
    </form>
  </div>    
  <script src="/static/assets/vendors/jquery/jquery.js"></script>   
  <script>
    $(function ($) {
      var emailFormat = /^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/
      $('#email').on('blur',function () {
        var value = $(this).val()
        if (!value || !emailFormat.test(value)) return
        $.get('/admin/api/avatar.php',{email: value},function (res) {   //大哥，要学会调试 写成了avatar了
          // if (!res) return
          // $('.avatar').attr('src',res)    
          $(".avatar").fadeOut(function () {
            $(this).on("load",function () {
              $(this).fadeIn()
            }).attr("src",res)
          })  
        }) 
      })
    })
  </script>              
</body>
</html>
