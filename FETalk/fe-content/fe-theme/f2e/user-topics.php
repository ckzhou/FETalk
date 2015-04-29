<?php 
	/**
	 *呈现用户发表的所有话题
	 */
	get_header();
	$user_profile=user_information('other');
	$user_id=$user_profile['id'];
	$username=$user_profile['username'];
	$page=get_page();
?>
<div class="content-wrapper">
	<div class="content main">
		<div class="topic-list">
		<h6 class="subject">F2E><?php echo $username;?>>话题列表</h6>
<?php 
	$topics=get_topic_number();
	if($topics>0):	//如果该用户已经发表过话题
		$topic_array=pagination(null,$user_id,$page);//第$page页的所有话题
		$topic_list=$topic_array['list'];
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
	?>
	<?php 
		$max_pagination=$topic_array['max_pagination'];
		if($max_pagination>1):
			$current_page=$topic_array['current_page'];
			if($current_page>1&&$current_page<$max_pagination){
				$previous_page=$current_page-1;
				$next_page=$current_page+1;
			}
			else if($current_page==1){
				$previous_page=1;
				$next_page=2;
			}
			else if($current_page==$max_pagination){
				$previous_page=$max_pagination-1;
				$next_page=$max_pagination;
			}
	?>
		<div class="pagination">
			<a href="fe-user.php?u=<?php echo $user_id;?>&c=topics&p=<?php echo $previous_page;?>" class="previous">«</a>
		<?php
			$i=1;
			while($i<=$max_pagination):
				if($i!=$current_page):
		?>
					<a href="fe-user.php?u=<?php echo $user_id;?>&c=topics&p=<?php echo $i;?>"><?php echo $i;?></a>
			<?php 
				else:
			?>
					<a href="javascript:" class="current-page"><?php echo $i;?></a>
		<?php
				endif;
				$i+=1;
			endwhile;
		?>
				<a href="fe-user.php?u=<?php echo $user_id;?>&c=topics&p=<?php echo $next_page;?>" class="next">»</a>
			</div>
	<?php
		endif;
	?>
<?php
	else:
?>
		<div class="empty-hint">该用户暂时还没发表过任何主题</div>
<?php 
	endif;
?>
	</div>
</div>
<?php
	require_once('userSidebar.php');
?>
</div>