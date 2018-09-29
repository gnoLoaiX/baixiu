<?php 

// 因为这个 sidebar.php 是被 index.php 载入执行，所以 这里的相对路径 是相对于 index.php
// 如果希望根治这个问题，可以采用物理路径解决
$current_page = isset($current_page) ? $current_page : ''; //建议加上
require_once '../functions.php';                           
//为什么不是../../因为这是公共的代码在index、等多处载入。require_once可以不写，因为index已经载入，但是写的严谨一些防止被其他文件载入
$current_user = xiu_get_current_user();        
//这里取得是session里面数据，并没有取数据库的数据，发生改变时再次刷新不会发生变化。重新登录才存到session才有，如果说你怕信息不同步，更新不及时，有解决方案把id传到session里面，但是没有必要


?>
<div class="aside">
  <div class="profile">
    <img class="avatar" src="<?php echo $current_user['avatar']; ?>">
    <h3 class="name"><?php echo $current_user['nickname']; ?></h3>
  </div>
  <ul class="nav">
    <li <?php echo $current_page === 'index'?'class="active"':''; ?>>
      <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
    </li>
    <?php $menu_posts = array('posts','post-add','categories');?>
    <!-- 数组处理函数 in_array检测存在 -->
    <li<?php echo in_array($current_page,$menu_posts) ?' class="active"':''; ?>>  <!-- 注意空格 -->
      <a href="#menu-posts"<?php echo in_array($current_page, $menu_posts) ? '' : 'class="collapsed"' ?> data-toggle="collapse">
        <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
      </a>
      <ul id="menu-posts" class="collapse<?php echo in_array($current_page, $menu_posts) ? ' in' : '' ?>">                                                                          <!-- 注意空格 -->
        <li<?php echo $current_page === 'posts' ? ' class="active"' : '' ?>><a href="/admin/posts.php">所有文章</a></li>
        <li<?php echo $current_page === 'post-add' ? ' class="active"' : '' ?>><a href="/admin/post-add.php">写文章</a></li>
        <li<?php echo $current_page === 'categories' ? ' class="active"' : '' ?>><a href="/admin/categories.php">分类目录</a></li>
      </ul>
    </li>
    <li<?php echo $current_page === 'comments'?'class="active"':''; ?>>
      <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
    </li>
    <li<?php echo $current_page === 'users'?'class="active"':''; ?>>
      <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
    </li>
    <?php $menu_settings = array('nav-menus', 'slides', 'settings'); ?>
    <li<?php echo in_array($current_page, $menu_settings) ? ' class="active"' : ''; ?>>
      <a href="#menu-settings"<?php echo in_array($current_page, $menu_settings) ? '' : ' class="collapsed"' ?> data-toggle="collapse">
        <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
      </a>
      <ul id="menu-settings" class="collapse<?php echo in_array($current_page, $menu_settings) ? ' in' : '' ?>">
        <li<?php echo $current_page === 'nav-menus' ? ' class="active"' : '' ?>><a href="/admin/nav-menus.php">导航菜单</a></li>
        <li<?php echo $current_page === 'slides' ? ' class="active"' : '' ?>><a href="/admin/slides.php">图片轮播</a></li>
        <li<?php echo $current_page === 'settings' ? ' class="active"' : '' ?>><a href="/admin/settings.php">网站设置</a></li>
      </ul>
    </li>                     <!-- 点击设置-导航菜单，会不展开？ -->
  </ul>
</div>
