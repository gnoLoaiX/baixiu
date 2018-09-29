<?php 
/**
 * 根据用户邮箱获取用户头像
 * email => image
 */
require_once '../../config.php';

if (empty($_GET['email'])) {
	exit('缺少必要参数');
}
$email = $_GET['email'];
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
	exit('连接数据库失败');
}
$res = mysqli_query($conn, "select avatar from users where email = '{$email}' limit 1;"); //选择列
if (!$res) {
	exit('数据库查询失败');
}
$row = mysqli_fetch_array($res);	//拽得一行数据，返回关联数组
echo $row['avatar'];					//关联数组里的键就是表里面得列