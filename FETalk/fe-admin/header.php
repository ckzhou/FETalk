<?php
	if(CALL_TOKEN!='f2ecreek'){
		exit('404!not found!');
	}
	require_once('../fe-include.php');
	if(!_checkLogin('admin')){
		exit('404!not found!');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin>>dashboard</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/reset.css"/>
<link rel="stylesheet" type="text/css" href="css/header.css"/>
<link rel="stylesheet" type="text/css" href="css/menubar.css"/>
<link rel="stylesheet" type="text/css" href="css/basic.css"/>
<?php
	$path=$_SERVER['PHP_SELF'];
	$components=explode("/",$path);
	$file=explode(".",array_pop($components))[0].".css";
?>
<link rel="stylesheet" type="text/css" href="css/<?php echo $file;?>"/>
<link rel="shortcut icon" href="../e.ico"/>
<script type="text/javascript" src="js/basic.js"></script>
</head>
<body>
	<div class="header">
		<div class="header-left">
			<ul class="header-options" id="parent-options-list">
				<li class="small-logo" id="parent-option-1"><a href="#" title="关于FETalk"><img src="css/image/logo.png" alt="small logo"/></a>
				</li>
				<li class="administrator" id="parent-option-2"><a href="javascript:" id="left-username"><?php echo $_SESSION['admin'];?></a>
					<ul class="sub-options">
						<li><a href="<?php echo _path('home');?>">回到站点</a></li>
					</ul>
				</li>
				<li class="new-item" id="parent-option-3"><a href="javascript:">新建</a>
					<ul class="sub-options">
						<li><a href="category.php">分类</a></li>
						<li><a href="node.php">节点</a></li>
						<li><a href="setting.php">页面</a></li>
						<li><a href="user.php">用户</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="header-right">
			<div class="user-info" id="user-info">
				<a href="javascript:" id="account-option"><span class="administrator" id="administrator">您好，<?php echo $_SESSION['admin'];?></span><img src="../fe-content/fe-face/admin.png" class="avatar-thumbnail" alt="avatar-thumbnail"/></a>
				<div class="detail-user-info" id="detail-user-info">
					<a href="user.php" class="big-avatar"><img src="../fe-content/fe-face/big_admin.png" alt="big avatar" /></a>
					<div class="account-operation">
						<a href="user.php" class="username" id="right-username"><?php echo $_SESSION['admin'];?></a>
						<a href="user.php" class="operation">编辑我的个人资料</a>
						<a href="logic.php?act=out" class="operation logout">登出</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	