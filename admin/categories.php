<?php 

require_once '../functions.php';
xiu_get_current_user();
//前面两行一定要写、不然会出现载入错误，一步一步去试在出现的错误中解决问题

/**
 * 封装代码 xiu_execute
 * @var [type]
 */
function add_category () {
  if (empty($_POST['name']) || empty($_POST['slug'])) { 
      $GLOBALS['message'] = '请完整填写表单！';
      $GLOBALS['success'] = false;
      return;
  }
  $name = $_POST['name'];
  $slug = $_POST['slug'];     //模板字符串拼接{}， $rows得到受影响行数
  $rows = xiu_execute("insert into categories values (null, '{$slug}', '{$name}');");  
  $GLOBALS['success'] = $rows > 0;
  $GLOBALS['message'] = $rows <= 0 ? '添加失败！' : '添加成功！';
}

/**
 * 添加功能
 * @param string
 */
function edit_category() {
  global $current_edit_category;

  // 接收并保存
  $id = $current_edit_category['id'];
  $name = empty($_POST['name']) ? $current_edit_category['name'] : $_POST['name'];
  $current_edit_category['name'] = $name;           //同步数据 这里写了之后再次不能提交，在MySQL做了机制处理
  $slug = empty($_POST['slug']) ? $current_edit_category['slug'] : $_POST['slug'];
  $current_edit_category['slug'] = $slug;

  // insert into categories values (null, 'slug', 'name');
  $rows = xiu_execute("update categories set slug = '{$slug}', name = '{$name}' where id = {$id}");
  $GLOBALS['success'] = $rows > 0;
  $GLOBALS['message'] = $rows <= 0 ? '更新失败！' : '更新成功！';
}

if (!empty($_GET['id'])) {                          //可以改加else，在baixiu_dev
  //--->客户端通过 URL 传递了一个id
  //--->客户端是要来拿一个修改数据的表单
  //--->需要拿到用户想要修改的数据
  $current_edit_category = xiu_fetch_one('select * from categories where id = ' . $_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
  //一旦表单提交请求并且没有通过 URL 提交 ID 就意味着要添加数据
  if (empty($_GET['id'])) {
    add_category();                         //不是每一次POST都是add_category
  } else {
    edit_category();
  }
}

/**
 * 如果修改操作与查询操作在一起，一定是先做修改，再查询
 * @var [type]
 */
$categories = xiu_fetch_all('select * from categories;');     //查数据之后，循环遍历

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 有很多方法-->
      <?php if (isset($message)): ?>
        <?php if ($success): ?>                  <!-- 有message就已经success -->
          <div class="alert alert-success">      <!-- bootstrap的代码 -->
            <strong>成功！</strong><?php echo $message; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-danger">      
            <strong>错误！</strong><?php echo $message; ?>
          </div>
        <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
        <?php if (isset($current_edit_category)): ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $current_edit_category['id']; ?>" method="post">
            <h2>编辑《<?php echo $current_edit_category['name']; ?>》</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo $current_edit_category['name']; ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo $current_edit_category['slug']; ?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
            </div>
          </form>
        <?php else: ?>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" id="btn_delete" href="/admin/categories_delete.php" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $item): ?>        <!-- 值赋值给$item -->
              <tr>
                <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                <td><?php echo $item['name'] ?></td>
                <td><?php echo $item['slug'] ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/categories_delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs">删除</a>                            <!-- 用绝对路径去写 -->
                </td>
              </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php echo $current_page = 'categories'?>
  <?php include 'inc/sidebar.php'?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>     <!-- jQ实现细长条 -->
  <script>
    //version 2 这种方法不同通过在url中直接传参数，二而是间接通过jQuery 原型方法实现
    $(function ($) {
      var $tbodyCheckboxs = $('tbody input');
      var $btnDelete = $('#btn_delete');

      var allCheckeds = []              
      $tbodyCheckboxs.on('change', function () {
        var id = $(this).data('id')

        if ($(this).prop('checked')) {
          allCheckeds.push(id)                             //增加元素
        } else {
          allCheckeds.splice(allCheckeds.indexOf(id), 1)   //移除元素 ，传入下标 每一次触发按钮都执行一次函数
        }

        // 根据剩下多少选中的 checkbox 决定是否显示删除
        allCheckeds.length ? $btnDelete.fadeIn() : $btnDelete.fadeOut()  //length为真就是有数组
        $btnDelete.prop('search', '?id=' + allCheckeds)                  //动态设置a链接的地址
        // btnDelete.attr('href','/admin/categories.php?id=' + allCheckeds )这样写把数据写死了，只有?的话就是当前地址，通过另外一种方式设置
      })

      // // version 1 ===功能仅用于：批量操作的显示与隐藏：循环次数比较多，都是每次都是DOM操作==============
      // $tbodyCheckboxs.on('change', function () {
      //   var flag = false
      //   $tbodyCheckboxs.each(function (i, item) {
      //     if ($(item).prop('checked')) {
      //       flag = true
      //     }
      //   })

      //   flag ? $btnDelete.fadeIn() : $btnDelete.fadeOut()
      // })

    })
  </script>
</body>
</html>
