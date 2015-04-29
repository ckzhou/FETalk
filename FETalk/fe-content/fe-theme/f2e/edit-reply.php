<?php 
	/**
	 *编辑已经发表的回复
	 */
	$original_reply=get_original_reply();
	get_header();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h6 class="subject">编辑回复</h6>
		<?php echo get_error_file();?>
		<form class="edit-form" method="POST" action="fe-edit.php" id="reply-form">
			<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>" />
			<input type="hidden" name="reply-id" value="<?php echo $original_reply['id'];?>"/>
			<input type="hidden" name="topic-id" value="<?php echo $original_reply['topic_id'];?>"/>
			<textarea name="new-text" class="new-text" id="reply-text"><?php echo $original_reply['text'];?></textarea>
			<input type="submit" name="edit-reply" class="modify-btn" id="reply-btn" value="立即修改"/>
		</form>
	</div>
<?php
	require_once('userSidebar.php');
?>
</div>
<script type="text/javascript" src="<?php echo _path('theme_url');?>/js/checkReply.js"></script>
