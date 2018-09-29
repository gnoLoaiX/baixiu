<?php  

if (empty($_GET['id'])) {
	exit('缺少必要参数');
}

$id = (int)$_GET['id'];									//转整数防攻击

require_once '../functions.php';
xiu_execute('delete from categories where id = '. $id); 
header('location: /admin/categories.php');

// xiu_execute('delete from categories where id in (' . $id . ');'); 好像都一样的效果
// 区别在于以前传一个id过来，现在是逗号方式传多个id，支持批量删除在客户端就这么简答