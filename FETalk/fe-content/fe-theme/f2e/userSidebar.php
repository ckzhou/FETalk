<?php
if(!_checkLogin()):
?>
<div class="content sidebar">
	<div class="site-definition"/>
		<h3 class="f2e">F2E=Front-End Engineer</h3>
		<p class="definition">前端工程师高端交流分享社区</p>
	</div>
	<div class="functions">
		<div class="login-register">
			<a href="fe-login.php" class="login-link btn">登录</a><a href="fe-register.php" class="register-link btn">注册</a>
		</div>
		<div class="forgot-pwd">
			已注册用户也可以<a href="" class="findPwd-link">找回密码</a>
		</div>
	</div>
</div>
<?php
else:
	$user_id=get_user_id();
?>
	<div class="content sidebar account-info">
		<div class="account-user">
			<a href="fe-user.php?u=<?php echo $user_id;?>"><img src="<?php echo fe_userFace(null,'big');?>" alt="user face" class="big-header-face"/></a><span class="username"><?php echo get_username();?></span>
		</div>
		<div class="account-done">
			<div class="done"><a href="fe-user.php?u=<?php echo $user_id;?>&c=topics&p=1" class="number"><b><?php echo get_topic_number();?></b></a><span>主题</span></div>
			<div class="done"><a href="fe-user.php?u=<?php echo $user_id;?>&c=replies&p=1" class="number"><b class="number"><?php echo get_reply_number();?></b></a><span>回复</span></div>
			<div class="done"><a href="fe-user.php?u=<?php echo $user_id;?>&c=collections&p=1" class="number"><b class="number"><?php echo get_collection('number');?></b></a><span>收藏</span></div>
			<div class="done last-done"><b class="number"><?php echo get_reputation();?></b><span>威望</span></div>
		</div>
	</div>
<?php endif;?>