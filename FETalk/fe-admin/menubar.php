<?php 
	if(CALL_TOKEN!='f2ecreek'){
		header('Location:http://www.ck.com/ckcom');
		exit;
	}
?>
<div class="menubar" id="menu-bar">
	<ul class="menu-list" id="menu">
		<li class="menu-list-item admin"><a href="admin.php"><span></span>仪表盘</a>
			<div class="arrow-icon"></div>
			<ul class="sub-menu-list">
				<li id="selected"><a href="admin.php">首页</a></li>
			</ul>
		</li>
		<li class="menu-list-item category"><a href="category.php"><span></span>分类</a>
			<div class="arrow-icon"></div>
			<ul class="sub-menu-list">
				<li><a href="category.php">所有分类</a></li>
				<li><a href="category.php">新建分类</a></li>
			</ul>
		</li>
		<li class="menu-list-item node"><a href="node.php"><span></span>节点</a>
			<div class="arrow-icon"></div>
			<ul class="sub-menu-list">
				<li><a href="node.php">所有节点</a></li>
				<li><a href="node.php">新建节点</a></li>
			</ul>
		</li>
		<li class="menu-list-item topic"><a href="topic.php?content=topic"><span></span>话题</a>
			<div class="arrow-icon"></div>
			<ul class="sub-menu-list">
				<li><a href="topic.php?content=topic">最近的话题</a></li>
				<li><a href="topic.php?content=search">搜索话题</a></li>
			</ul>
		</li>
		<li class="menu-list-item user"><a href="user.php"><span></span>用户</a>
			<div class="arrow-icon"></div>
			<ul class="sub-menu-list">
				<li><a href="user.php">新用户</a></li>
				<li><a href="user.php">搜索用户</a></li>
			</ul>
		</li>
		<li class="menu-list-item setting"><a href="setting.php"><span></span>设置</a>
			<div class="arrow-icon"></div>
			<ul class="sub-menu-list">
				<li><a href="setting.php">设置主题</a></li>
				<li><a href="setting.php">设置页面</a></li>
				<li><a href="setting.php">设置我的个人资料</a></li>
			</ul>
	</ul>
</div>

