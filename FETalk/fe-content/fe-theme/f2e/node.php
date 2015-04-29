<?php 
	get_header();
	$node=current_node();
	$node_id=$node['node_id'];
	$node_name=$node['node_name'];
?>
<div class="content-wrapper">
	<div class="content main">
		<div class="create-topic">
			<div class="create-topic-left">F2E><?php echo $node_name;?></div>
			<div class="create-topic-right">
				<a href="fe-create.php?n=<?php echo $node_id;?>" class="create-topic-btn" id="create-topic-btn">创建主题</a>
			</div>
		</div>
		<div class="topic-list">
<?php 
	$topic_array=pagination($node_id);
	$topic_list=$topic_array['list'];
	$number=count($topic_list);
	if($number>0):	//如果该节点下有人发表了帖子
		foreach($topic_list as $topic_item):
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
	else:
?>	
		<p class='no-topic'>该节点下还没有人发表帖子</p>
<?php 
	endif;
?>
		</div>
	</div>
<?php 
	require_once('userSidebar.php');
?>
</div>