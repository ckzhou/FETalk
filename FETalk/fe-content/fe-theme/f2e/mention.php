<?php 
	/**
	 *呈现所有未读提醒
	 */
	$not_read=get_mention();
	get_header();
?>
<div class="content-wrapper">
	<div class="content main">
		<div class="mention-list">
			<h6 class="subject">F2E>提醒消息</h6>
			<h6 class="subsubject"><a href="fe-mention.php?c=at" title="查看@你的历史记录">@我的</a></h6>
<?php 
	$at=$not_read['at'];
	$at_number=$at['number'];
	if($at_number>0):
		$at_items=$at['items'];
		foreach($at_items as $item):
			if(!isset($item['reply_text'])):
?>	
				<p class="at_item"><a href="fe-user.php?u=<?php echo $item['from_uid'];?>"><?php echo $item['from_user'];?></a>
				在发表话题<a href="fe-topic.php?t=<?php echo $item['topic_id'];?>"><?php echo $item['topic_title'];?></a>时@了你
				</p>
				<div class="text"><?php echo $item['topic_text'];?></div>

		<?php 
			else:
		?>
				<p class="at_item"><a href="fe-user.php?u=<?php echo $item['from_uid'];?>"><?php echo $item['from_user'];?></a>
				在回复话题<a href="fe-topic.php?t=<?php echo $item['topic_id'];?>"><?php echo $item['topic_title'];?></a>时@了你</p>
				<div class="text"><?php echo $item['reply_text'];?></div>
<?php
			endif;
		endforeach;
	else:
?>
		<p class='no-mention'>您暂时还没收到新的@消息</p>
<?php 
	endif;
?>
			<h6 class="subsubject"><a href="fe-mention.php?c=letter" title="查看私信对话记录">私信</a></h6>
<?php 
	$letter=$not_read['letter'];
	$letter_number=$letter['number'];
	if($letter_number>0):
		$letter_items=$letter['items'];
		foreach($letter_items as $item):
?>
			<p class="letter_item"><a href="fe-user.php?u=<?php echo $item['from_uid'];?>"><?php echo $item['from_user'];?></a>
			给你发了私信
			</p>
			<div class="text"><?php echo $item['content'];?></div>
	<?php 
		endforeach;
	else:
	?>
		<p class='no-mention'>您暂时没收到新的私信消息</p>
<?php
	endif;
?>
		</div>
	</div>
<?php 
	require_once('userSidebar.php');
?>
</div>