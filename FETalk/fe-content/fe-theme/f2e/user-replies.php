<?php 
	/**
	 *呈现用户已发表的所有回复
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
		<h6 class="subject">F2E><?php echo $username;?>>回复列表</h6>
<?php 
	$replies_num=get_reply_number();
	if($replies_num>0):	//如果该用户已经发表过回复
		$reply_array=extract_reply(null,$user_id,$page);
		$reply_list=$reply_array['list'];//获取第$page页的回复
		foreach($reply_list as $reply):
			$reply_text=$reply['reply_text'];
			$topic=extract_topic_all($reply['topic_id']);
			$topic_title=$topic['topic_title'];
			$topic_author=$topic['author_name'];
			$topic_id=$topic['topic_id'];
	?>
		<div class="reply">
			<div class="reply-header">回复了<span class="reply-topic-author"><?php echo $topic_author;?></span>创建的主题<a href="fe-topic.php?t=<?php echo $topic_id;?>" class="reply-topic-title"><?php echo $topic['topic_title'];?></a></div>
			<div class="reply-content"><?php echo $reply_text;?></div>
		</div>

	<?php 
		endforeach;
	?>
	<?php 
		$max_pagination=$reply_array['max_pagination'];
		if($max_pagination>1):
			$current_page=$reply_array['current_page'];
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
			<a href="fe-user.php?u=<?php echo $user_id;?>&c=replies&p=<?php echo $previous_page;?>" class="previous">«</a>
		<?php
			$i=1;
			while($i<=$max_pagination):
				if($i!=$current_page):
		?>
					<a href="fe-user.php?u=<?php echo $user_id;?>&c=replies&p=<?php echo $i;?>"><?php echo $i;?></a>
			<?php 
				else:
			?>
					<a href="javascript:" class="current-page"><?php echo $i;?></a>
		<?php
				endif;
				$i+=1;
			endwhile;
		?>
				<a href="fe-user.php?u=<?php echo $user_id;?>&c=replies&p=<?php echo $next_page;?>" class="next">»</a>
			</div>
	<?php
		endif;
	?>
<?php
	else:
?>	
		<div class="empty-hint">该用户暂时还没发表过任何回复</div>
<?php 
	endif;
?>
	</div>
</div>
<?php 
	require_once('userSidebar.php');
?>
</div>