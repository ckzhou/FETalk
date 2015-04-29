<?php 
	get_header();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h3 class="subject">创建新主题</h3>
		<?php echo get_error_file();?>
		<form action="fe-create.php" method="POST" class="create-form" id="create-form">
			<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>"/>
			<input type="text" name="create-title" class="create-title" id="create-title" placeholder="主题"/>
			<textarea name="create-text" class="create-text" id="create-text" placeholder="正文"></textarea>
			<input type="submit" name="create-btn" class="create-btn" id="create-btn" value="立即创建"/>
		</form>
	</div>
<?php 
	require_once('userSidebar.php');
?>
</div>
<script type="text/javascript" src="<?php echo _path('theme_url').'/js/checkTopic.js';?>"></script>