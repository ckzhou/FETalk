<?php 
	get_header();
	if(isset($_GET['p'])&&is_numeric($_GET['p'])){
		$page=intval($_GET['p']);
	}
	else{
		$page=1;
	}
	$topic_array=pagination(null,null,$page);//第$page页的所有话题
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<div class="create-topic">
			<div class="create-topic-left">
				<a href="fe-node.php?n=18" class="create-topic-item">分享</a>
				<a href="fe-node.php?n=19" class="create-topic-item">招聘</a>
				<a href="fe-node.php?n=20" class="create-topic-item">问与答</a>
				<a href="fe-node.php?n=8" class="create-topic-item">开源项目</a>
				<a href="fe-node.php?n=9" class="create-topic-item">算法</a>
				<a href="fe-node.php?n=3" class="create-topic-item">JavaScript</a>
				<a href="fe-node.php?n=12" class="create-topic-item">书籍</a>
				<a href="fe-node.php?n=21" class="create-topic-item">社区开发</a>
			</div>
			<div class="create-topic-right">
				<a href="javascript:" class="create-topic-btn" id="create-topic-btn">创建主题</a>
				<ul class="create-topic-list" id="create-topic-list">
		<?php 
			$hotNodes=fe_hotNodes();
			if(is_array($hotNodes)):
				foreach($hotNodes as $node):
		?>
					<li><a href="fe-create.php?n=<?php echo $node['id'];?>"><?php echo $node['nName'];?></a></li>
		<?php 
				endforeach;
			endif;
		?> 
				</ul>
			</div>
		</div>
		<div class="topic-list">
<?php 
	$topic_list=$topic_array['list'];
	$number=count($topic_list);
	if($number>0):
		foreach($topic_list as $topic_item):	
?>
			<div class="topic-item">
		<?php 
			if(isset($topic_item['type'])):	//如果是好友动态条目
				$topic_id=$topic_item['topic_id'];
				$author_id=get_user_id($topic_id);
				$face_path=fe_userFace($author_id,'big');
				echo "<a href='fe-user.php?u={$author_id}' class='author-face'><img src='{$face_path}'/></a>";
				echo '<div class="topic">';
				$create_type=$topic_item['type'];
				if($create_type=='create_reply'){
					$users=$topic_item['user_id'];
					foreach($users as &$user_id){
						$username=get_username($user_id);
						$user_id="<a href='fe-user.php?u={$user_id}'>{$username}</a>";
					}
					echo "<p class='active-users'>你关注的".implode(',',$users).'回复了话题</p>';
				}
				else{
					$user_id=$topic_item['author_id'];
					$username=get_username($user_id);
					echo "<p class='active-users'>你关注的<a href='fe-user.php?u={$user_id}'>{$username}</a>发表了话题</p>";
				}
				$title=get_title($topic_id);
				echo "<h6><a href='fe-topic.php?t={$topic_id}'>{$title}</a></h6>";
				echo "</div>";
			else:	//如果是社区动态条目
		?>
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
			<?php 
				endif;
			?>
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
				<a href="index.php?p=<?php echo $previous_page;?>" class="previous">«</a>
		<?php
			$i=1;
			while($i<=$max_pagination):
				if($i!=$current_page):
		?>
					<a href="index.php?p=<?php echo $i;?>"><?php echo $i;?></a>
			<?php 
				else:
			?>
					<a href="javascript:" class="current-page"><?php echo $i;?></a>
		<?php
				endif;
				$i+=1;
			endwhile;
		?>
				<a href="index.php?p=<?php echo $next_page;?>" class="next">»</a>
			</div>
<?php
		endif;
	else:	
?>
		<p class='no-topic'>还没有人发布帖子</p>
<?php 
	endif;
?>
		</div>
	</div>
<?php 
	require_once('userSidebar.php');
	require_once('nodeSidebar.php');
	require_once('statusSidebar.php');
?>
</div>
<script type="text/javascript">
	addEvent(window,'click',ctrlTopicList);
	function ctrlTopicList(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var id=target.getAttribute('id');
		var topicList=$('create-topic-list');
		if(id==='create-topic-btn'){
			if(topicList.style.display==='block'){
				topicList.style.display='none';
			}
			else{
				topicList.style.display='block';
			}
		}
		else{
			topicList.style.display='none';
		}
	}
</script>