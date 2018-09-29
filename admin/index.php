<?php

require_once '../functions.php';

// 判断用户是否登录一定是最先去做
xiu_get_current_user();

// 获取界面所需要的数据
// 重复的操作一定封装起来，这里每做一次查询都要操作一次API
// count(1)和count(*) 的区别
// as num 查询结果另外起一个别名，叫做num，这样理解就可以了
//这样写法严禁不严谨呢？一旦涉及取数组索引的时候都要考虑万一没有的问题。这时候分开写然后通过isset判断一下(强调一下)
$posts_count = xiu_fetch_one('select count(1) as num from posts;')['num'];      //在下面修改输出

$categories_count = xiu_fetch_one('select count(1) as num from categories;')['num'];

$comments_count = xiu_fetch_one('select count(1) as num from comments;')['num'];

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php';?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_count ?></strong>篇文章（<strong>2</strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories_count ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments_count ?></strong>条评论（<strong>1</strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <canvas id="chart"></canvas>                                       <!-- 引入canvas文件 -->
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  <?php echo $current_page = 'index'?>
  <?php include 'inc/sidebar.php'?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/chart/Chart.js"></script>
  <script>NProgress.done()</script>
  <script>
    var ctx = document.getElementById('chart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [
          {
            data: [<?php echo $posts_count; ?>, <?php echo $categories_count; ?>, <?php echo $comments_count; ?>],
            backgroundColor: [
              'deeppink',
              'blue',
              'green',
            ]
          }
        ],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: [
          '文章',
          '分类',
          '评论'
        ]
      }
    });
  </script>
</body>
</html>
