<?php 
	/**
	 *编辑已发表的话题
	 */
	$original_topic=get_original_topic();
	get_header();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h6 class="subject">编辑话题</h6>
		<?php echo get_error_file();?>
		<form method="POST" action="fe-edit.php" class="edit-form" id="create-form">
			<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>"/>
			<input type="hidden" name="topic-id" value="<?php echo $original_topic['id'];?>" />
			<input type="text" name="new-title" class="new-title" value="<?php echo $original_topic['title'];?>" id="create-title"/>
			<textarea class="new-text" name="new-text" id="create-text"><?php echo $original_topic['text'];?></textarea>
			<input type="submit" name="edit-topic" value="立即修改" class="modify-btn" id="create-btn"/>
		</form>
	</div>
<?php
	require_once('userSidebar.php');
?>
</div>
<script type="text/javascript" src="<?php echo _path('theme_url').'/js/checkTopic.js';?>"></script>
