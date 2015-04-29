<?php 
/*
product:theme for ckcom
name:f2e
version:0.1
author:creek
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>F2E - 前端技术社区</title>
<meta http-equiv="Content-type" content="text/html;charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="<?php echo _path('theme_url');?>/css/basic.css"/>
<?php
	$file=$_SERVER['PHP_SELF'];
	if(strpos($file,'-')){
		$temp=array_pop(explode('-',$file));
	}
	else{
		$temp=array_pop(explode('/',$file));
	}
	$cssFile=explode('.',$temp)[0].'.css';
?>
<link rel="stylesheet" type="text/css" href="<?php echo _path('theme_url').'/css/'.$cssFile;?>"/>
<link rel="shortcut icon" href="e.ico"/>
<script type="text/javascript" src="<?php echo _path('theme_url');?>/js/basic.js"></script>
<?php 
	$is_login=false;
	if(_checkLogin()):
		$is_login=true;
?>
	<script type="text/javascript">
		window.onload=function(){
			var xhr=XHR();
			if(xhr){
				xhr.open('GET','fe-action.php?act=unread',true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var at_number=json.mention;
						var letter_number=json.letter;
						if(at_number||letter_number){
							var mailBox=$('message');
							mailBox.setAttribute('title','你有新的未读提醒');
							mailBox.className+=' unread';
						}
					}
				}
				xhr.send(null);
			}
		}
	</script>
<?php 
	endif;
?>
</head>
<body>
	<div class="header-wrapper">
		<div class="header">
			<div class="header-left">
				<a href="<?php echo _path('home');?>" class="logo"><img src="<?php echo _path("theme_url");?>/images/logo-dance.png" alt="logo of f2e"/></a>
				<?php 
					if($is_login):
				?>
					<span class="placeholder placeholder-left"></span>
					<a href="fe-mention.php" class="message" title="暂时没有未读提醒" id="message"><span class="message-status"></span></a>
					<span class="placeholder placeholder-right"></span>
				<?php 
					endif;
				?>
				<ul class="menu">
				<li><a href="<?php echo _path('home');?>">社区</a></li>
				<?php 
					$menuArr=fe_menu();
					if(is_array($menuArr)){
						foreach($menuArr as $item){
							echo "<li><a href='"._path('home')."/fe-page.php?pId=".$item['id']."'>".$item['pName']."</a></li>";
						}
					}
				?>
				</ul>
				<form class="search">
					<input type="text" class="searchBox" id="searchBox"/>
				</form>
				<script type="text/javascript">
					var searchBox=$("searchBox");
					var initialWidth=150;
					var finalWidth=165;
					addEvent(searchBox,"focus",expandSearchBox);
					addEvent(searchBox,"blur",shortenSearchBox);
				</script>
			</div>
			<div class="header-right">
			<?php 
				$status=fe_switcher();
			?>
				<ul class="status">
			<?php 
				if($status['login']):
					$user_id=get_user_id(null,true);
			?>
				<li><a href="fe-user.php?u=<?php echo $user_id;?>"><img src="<?php echo fe_userFace($user_id);?>" alt="user face" class="header-face"/></a></li>
			<?php endif;?>
					<li><a href="<?php echo _path('home');?>">首页</a></li>
					<li><?php echo $status['link'][0];?></li>
					<li><?php echo $status['link'][1];?></li>
				</ul>
			</div>
		</div>
	</div>