<?php
	/**
	 *呈现用户已收藏的所有话题
	 */
	get_header();
	$user_profile=user_information('other');
	$user_id=$user_profile['id'];
	$username=$user_profile['username'];
	$page=get_page();
?>
<div class="content-wrapper">
	<div class="content main">
		<div class="reply-list">
			<h6 class="subject">F2E><?php echo $username;?>>收藏的主题列表</h6>
	<?php 
		$collect_number=get_collection('number');
		if($collect_number>0):
			$collect_array=get_collection();
			foreach($collect_array as $topic_item):
	?>
			<div class="topic-item">
			<a href="fe-user.php?u=<?php echo $topic_item['author_id'];?>" class="author-face"><img src="<?php echo fe_userFace($topic_item['author_id'],'big');?>"/></a>
			<div class="topic">
				<h6><a href="fe-topic.php?t=<?php echo $topic_item['topic_id'];?>"><?php echo $topic_item['topic_title'];?></a></h6>
				<div class="meta-data">
					<a href="fe-node.php?n=<?php echo $topic_item['node_id'];?>" class="meta-node"><?php echo $topic_item['node_name'];?></a>
					<span class="meta-author">
							<i class="decorator"></i>
							<a href="fe-user.php?u=<?php echo $topic_item['author_id'];?>" class="author-name"><?php echo $topic_item['author_name'];?></a>
					</span>
			<?php 
				if(isset($topic_item['reply_amounts'])&&$topic_item['reply_amounts']>0):
			?>
					<span class="meta-time">
						<i class="decorator"></i>
						<?php echo format_time($topic_item['latest_reply_time']);?>
					</span>
					<span class="meta-replier">
						<i class="decorator"></i>
						最后回复来自
						<a href="fe-user.php?u=<?php echo $topic_item['latest_reply_uid'];?>" class="replier-name"><?php echo $topic_item['latest_replier'];?></a>
					</span>
					<a href="fe-topic.php?t=<?php echo $topic_item['topic_id'];?>" class="reply-amounts"><?php echo $topic_item['reply_amounts'];?></a>
			<?php 
				else:	
			?>
					<span class="meta-time">
						<i class="decorator"></i>
						<?php echo format_time($topic_item['created_time']);?>
					</span>
			<?php 
				endif;
			?>
				</div>
			</div>
		</div>
	<?php 
		endforeach;
	?>
	<?php 
		else:
	?>
			<div class="empty-hint">该用户暂时还没收藏过任何话题</div>
	<?php 
		endif;
	?>
		</div>
	</div>
<?php 
	require_once('userSidebar.php');
?>
</div>
	