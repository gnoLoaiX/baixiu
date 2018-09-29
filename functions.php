<?php 
/**
 * 封装大家公用的函数，这里可以再次封装，原则尽可能的代码不要有重复的
 * 
 */
session_start();										//放在了session里
require_once 'config.php';								//操作数据库需要载入

// 定义函数时一定要注意：函数名与内置函数冲突问题
// JS 判断方式：typeof fn === 'function'
// PHP 判断函数是否定义的方式： function_exists('get_current_user')  php打印false时什么都没有输出

/**
 * 获取当前登录用户信息，如果没有获取到则自动跳转到登录页面
 * @return [type] [description]
 */
function xiu_get_current_user () {  					//内置函数可能重名
  if (empty($_SESSION['current_login_user'])) {
    // 没有当前登录用户信息，意味着没有登录
    header('Location: /admin/login.php');
    exit(); // 没有必要再执行之后的代码，直接结束代码，不是的话后买你的代码会执行
  }
  return $_SESSION['current_login_user'];
}

/**
 * 通过一个数据库查询获取多条数据
 * => 索引数组套关联数组
 * @return [type] [description]
 */
function xiu_fetch_all ($sql) {
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$conn) {
    exit('连接失败');
  }

  $query = mysqli_query($conn, $sql);
  if (!$query) {
    // 查询失败 结束执行呢还是告诉查询失败 
    return false;
  }

  while ($row = mysqli_fetch_assoc($query)) {
    $result[] = $row;
  }

  mysqli_free_result($query);							//释放查询资源写不写都是关闭，php默认关闭
  mysqli_close($conn);									//炸桥 

  return $result;
}

/**
 * 获取单条数据
 * => 关联数组 有可能为空数组报出警告 多封装一次比较方便
 * @return [type] [description]
 */
function xiu_fetch_one ($sql) {
  $res = xiu_fetch_all($sql);
  return isset($res[0]) ? $res[0] : null;
}

/**
 * 非查询语句：执行一个增删改语句
 * @return [type] [description]
 */
function xiu_execute ($sql) {
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$conn) {
    exit('连接失败');
  }

  $query = mysqli_query($conn, $sql);
  if (!$query) {
    // 查询失败
    return false;
  }

  // 对于增删修改类的操作都是获取受影响行数
  $affected_rows = mysqli_affected_rows($conn);

  mysqli_close($conn);

  return $affected_rows;
}
