<?php
	/**
	 *FETalk安装文件，创建系统数据表以及初始化数据
	 */
	if(isset($_POST['mysql'])){
		$mysql_host=$_POST['mysql-host'];
		$mysql_user=$_POST['mysql-username'];
		$mysql_pwd=$_POST['mysql-password'];
		if(!$mysql_host||!$mysql_user||!$mysql_pwd){
			$setup=0;
		}
		else if($conn=@mysql_connect($mysql_host,$mysql_user,$mysql_pwd)){	//连接数据库成功
			$sysTables="CREATE DATABASE FETalk;
						USE FETalk;
						CREATE TABLE IF NOT EXISTS `fe_admin` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `username` varchar(20) NOT NULL,
						  `password` varchar(50) NOT NULL,
						  `email` varchar(30) NOT NULL,
						  `uniqid` varchar(50) NOT NULL,
						  `uKey` varchar(50) NOT NULL,
						  PRIMARY KEY (`id`),
						  UNIQUE KEY `eamil` (`email`),
						  UNIQUE KEY `email` (`email`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
						CREATE TABLE IF NOT EXISTS `fe_agreement` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `reply_id` int(10) unsigned NOT NULL,
						  `user_id` int(10) unsigned NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
						CREATE TABLE IF NOT EXISTS `fe_category` (
						  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
						  `cName` varchar(30) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_collect` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `uId` int(10) unsigned NOT NULL,
						  `tId` int(10) unsigned NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_concern` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `active_id` int(10) unsigned NOT NULL,
						  `passive_id` int(10) unsigned NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_dynamics` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `user_id` int(10) unsigned NOT NULL,
						  `topic_id` int(10) unsigned NOT NULL,
						  `type` enum('create_topic','create_reply') NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_face` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `uId` int(10) unsigned NOT NULL,
						  `fName` varchar(50) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_letter` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `from_uid` int(10) unsigned NOT NULL,
						  `to_uid` int(10) unsigned NOT NULL,
						  `cTime` int(10) unsigned NOT NULL,
						  `content` mediumtext NOT NULL,
						  `owner_id` int(10) unsigned NOT NULL,
						  `have_read` enum('not','yes') NOT NULL DEFAULT 'not',
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_mention` (
						  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
						  `from_uid` int(10) unsigned NOT NULL,
						  `to_uid` int(10) unsigned NOT NULL,
						  `topic_id` int(10) unsigned NOT NULL,
						  `reply_id` int(10) unsigned DEFAULT NULL,
						  `have_read` enum('not','yes') NOT NULL DEFAULT 'not',
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_menu` (
						  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
						  `pName` varchar(20) NOT NULL,
						  `template` varchar(20) NOT NULL DEFAULT '没有设置',
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_node` (
						  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
						  `nName` varchar(30) NOT NULL,
						  `cId` tinyint(3) unsigned NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_reply` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `uId` int(10) unsigned NOT NULL,
						  `tId` int(10) unsigned NOT NULL,
						  `text` mediumtext NOT NULL,
						  `cTime` int(10) unsigned NOT NULL,
						  `agree_times` int(10) unsigned NOT NULL DEFAULT '0',
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_topic` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `uId` int(10) unsigned NOT NULL,
						  `nodeId` tinyint(3) unsigned NOT NULL,
						  `title` varchar(56) NOT NULL,
						  `text` longtext NOT NULL,
						  `cTime` int(10) unsigned NOT NULL,
						  `click_times` int(10) unsigned NOT NULL DEFAULT '0',
						  `final_reply_time` int(10) unsigned NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `fe_user` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `username` varchar(20) NOT NULL,
						  `password` varchar(50) NOT NULL,
						  `email` varchar(30) NOT NULL,
						  `signature` varchar(50) DEFAULT NULL,
						  `city` varchar(50) DEFAULT NULL,
						  `blog` varchar(150) DEFAULT NULL,
						  `company` varchar(50) DEFAULT NULL,
						  `github` varchar(150) DEFAULT NULL,
						  `douban` varchar(150) DEFAULT NULL,
						  `weibo` varchar(150) DEFAULT NULL,
						  `introduction` varchar(140) DEFAULT NULL,
						  `regTime` int(10) unsigned NOT NULL,
						  `uniqid` varchar(50) NOT NULL,
						  `uKey` varchar(50) DEFAULT NULL,
						  `reputation` int(10) unsigned NOT NULL DEFAULT '0',
						  `dynamic_count` tinyint(3) unsigned NOT NULL DEFAULT '0',
						  `defriended` enum('not','yes') NOT NULL DEFAULT 'not',
						  PRIMARY KEY (`id`),
						  UNIQUE KEY `email` (`email`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
						CREATE TABLE IF NOT EXISTS `ck_vote` (
						  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						  `topic_id` int(10) unsigned NOT NULL,
						  `user_id` int(10) unsigned NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
						INSERT INTO fe_admin (username,password) VALUES('admin','6742d53f7fcdf39bba66e75fdd94785b');";
			$tables=explode(";",$sysTables);
			$counts=count($tables);
			for($i=0;$i<$counts;$i++){	//创建数据表
				$query_table=trim($tables[$i]);
				mysql_query($query_table);
			}
			//更新配置文件
			$configFile=dirname(__FILE__).'/fe-config/config.inc.php';
			$file_content=file_get_contents($configFile);
			$new_content=preg_replace('/define("SERVER",".*?")/','define("SERVER","'.$mysql_host.'")',$file_content);
			$new_content=preg_replace('/define("USERNAME",".*?")/','define("USERNAME","'.$mysql_user.'")',$new_content);
			$new_content=preg_replace('/define("PASSWORD",".*?")/','define("PASSWORD","'.$mysql_pwd.'")',$new_content);
			file_put_contents($configFile,$new_content);
			$setup=1;	//安装成功
		}
		else{	//mysql信息错误
			$setup=0;	//安装失败
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>Setup FETalk</title>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<style type="text/css">
	*{
		margin:0px;
		padding:0px;
	}
	.wrapper{
		width:800px;
		margin:35px auto;
		border:1px solid #d0d0d0;
		border-radius:5px;
		padding:20px;
	}
	.subject{
		text-align:center;
	}
	.desc{
		font-size:14px;
		height:50px;
		line-height:50px;
	}
	.form-control{
		overflow:hidden;
		margin-bottom:20px;
	}
	.form-control *{
		float:left;
	}
	em,i{
		font-size:14px;
		font-style:normal;
		display:block;
		texta-lign:left;
	}
	em{
		font-weight:bold;
		width:150px;
	}
	.mysql{
		margin:0 20px 0 50px;
		width:200px;
		height:25px;
		//line-height:25px;
		padding-left:5px;
		outline:none;
		border:1px solid #cecece;
		border-radius:3px;
	}
	.submit{
		outline:none;
		background-color:#f9f9f9;
		width:50px;
		height:30px;
		line-height:30px;
		text-align:center;
		border:1px solid #dfdfdf;
		border-radius:3px;
		cursor:pointer;
		margin-top:20px;
	}
	.submit:hover{
		border-color:#3d3d3d;
	}
</style>
</head>
<body>
<div class="wrapper">
<?php 
if(!isset($setup)||$setup==0):
	if(!isset($setup)):
?>
	<h1 class="subject">FETalk安装</h1>
	<p class="desc">请在下方填写您的Mysql数据库连接信息</p>
<?php
	else:
?>
	<h1 class="subject">安装失败</h1>
	<p class="desc">数据库连接错误，请填写正确的数据库信息</p>
<?php
	endif;
?>
	<form method="POST" action="setup.php" class="mysql-form">
		<div class="form-control">
			<em>用户名</em>
			<input type="text" class="mysql" name="mysql-username" />
			<i>您的MySQL用户名</i>
		</div>
		<div class="form-control">
			<em>密码</em>
			<input type="password" class="mysql" name="mysql-password"/>
			<i>您的Mysql密码</i>
		</div>
		<div class="form-control">
			<em>数据库主机</em>
			<input type="text" class="mysql" name="mysql-host" />
			<i>数据库服务器地址</i>
		</div>
		<div class="form-control">
			<input type="submit" class="submit" name="mysql" value="提交"/>
		</div>
	</form>
<?php
else:
?>
	<h1 class="subject">安装成功</h1>
	<p class="desc">网站后台管理默认用户名：admin，默认密码：feadmin</p>
<?php
endif;
?>
</div>
</body>
</html>