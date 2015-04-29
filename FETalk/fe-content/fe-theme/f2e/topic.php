<?php 
	get_header();
	$topic=extract_topic_all();
	if(_checkLogin()){
		$self_id=get_user_id(null,true);
	}
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<div class="topic-content">
			<div class="topic-header">
					<h3 class="topic-title" id="topic-title"><?php echo $topic['topic_title'];?></h3>
					<div class="meta-data">
						<a href="fe-node.php?n=<?php echo $topic['node_id'];?>" class="meta-node"><?php echo $topic['node_name'];?></a>
						<span>
							<i class="decorator"></i>
							<a href="fe-user.php?u=<?php echo $topic['author_id'];?>" class="meta-author"><?php echo $topic['author_name'];?></a>
						</span>
						<span>
							<i class="decorator"></i>
							发表于<?php echo format_time($topic['created_time']);?>
						</span>
				<?php 
					if(isset($topic['reply_amounts'])):
				?>
						<span>
							<i class="decorator"></i>
							最后回复来自<a href="fe-user.php?u=<?php echo $topic['author_id'];?>"><?php echo $topic['author_name'];?></a>
						</span>
						<span>
							<i class="decorator"></i>
							<?php echo format_time($topic['latest_reply_time']);?>
						</span>
				<?php 
					endif;
				?>
					</div>
					<a href="fe-user.php?u=<?php echo $topic['author_id'];?>" class="author-face"><img src="<?php echo fe_userFace($topic['author_id'],$type='big');?>"/></a>
			</div>
			<div class="topic-text">
			<?php 
				echo $topic['topic_text'];
			?>
			</div>
			<div class="topic-operation">
			<?php 
				echo topic_collection($topic['topic_id']);
			?>
				<a href="javascript:" id="share-weibo">新浪微博</a>
			<?php
				echo topic_vote($topic['topic_id']);
				if(isset($self_id)&&$topic['author_id']===$self_id){
					echo "<a id='edit-topic'href='fe-edit.php?c=topic&t={$topic['topic_id']}'>编辑</a>";
				}
			?>
				<span><?php echo $topic['click_times'];?>次点击</span>
			</div>
		</div>
<?php 
	if(isset($topic['reply_amounts'])):
?>
		<div class="topic-replies" id="topic-replies">
			<h6 class="subject">共收到<?php echo $topic['reply_amounts'];?>条回复</h6>
<?php 
		$replies=$topic['replies'];
		foreach($replies as $reply_item):
?>
			<div class="reply-item">
				<a href="fe-user.php?u=<?php echo $reply_item['reply_author_id'];?>" class="replier-face"><img src="<?php echo fe_userFace($reply_item['reply_author_id'],'big');?>" /></a>
				<div class="reply-content">
					<div class="reply-meta">
						<span class="reply-meta-left">
							<a href="fe-user.php?u=<?php echo $reply_item['reply_author_id'];?>"><?php echo $reply_item['reply_author_name'];?></a>
							<?php echo format_time($reply_item['reply_created_time']);?>
					<?php
						if(isset($self_id)&&$reply_item['reply_author_id']===$self_id){
							echo "<a href='fe-edit.php?c=reply&r={$reply_item['reply_id']}' id='edit-reply'>编辑</a>";
						}
					?>
						</span>
						<span class="reply-meta-right">
							<span class="agree-amounts" id="agree-amounts"><?php echo $reply_item['reply_agree_times'];?></span>
						<?php 
							echo reply_agreement($reply_item['reply_id']);
						?>
							<a href="#reply-text" class="reply-someone" id="reply-to">
								<img src="<?php echo _path('theme_url');?>/images/reply.png"/>
							</a>
						</span>
					</div>
					<div class="reply-text"><?php echo $reply_item['reply_text'];?></div>
				</div>
			</div>
<?php 	
	endforeach;
?>
		</div>
<?php 
	else:
?>
		<div class="no-reply">暂无回复，说出你的观点吧</div>
<?php 
	endif;
?>
<?php 
	if(_checkLogin()):
?>
		<!--如果已经登录，显示回复框-->
		<div class="reply-box" id="reply-box">
			<h6 class="subject">创建新回复</h6>
			<?php echo get_error_file();?>
			<form class="reply-form" action="fe-topic.php" method="POST" id="reply-form">
				<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>"/>
				<textarea name="reply-text" placeholder="回复内容" id="reply-text"></textarea>
				<input type="submit" name="submitReply" value="立即回复" class="submitReply" id="reply-btn"/>
			</form>
		</div>
<?php 
	else:
?>
		<!--否则显示登录注册按钮-->
		<div class="login-box">
			<h6 class="subject">登录即可参与回复</h6>
			<a href="<?php echo _path('login');?>" class="login button">登录</a>
			<a href="<?php echo _path('register');?>" class="register button">注册</a>
		</div>
<?php 
	endif;
?>
	</div>
<?php 
	require_once('userSidebar.php');
?>
</div>
<script type="text/javascript" src="<?php echo _path('theme_url').'/js/checkReply.js';?>"></script>
<script type="text/javascript">
	/**
	 *利用事件代理绑定点击事件
	 */
	var mainDiv=$('main');
	addEvent(mainDiv,'click',handler);
	 
	/**
	 *handler	点击事件的处理函数，利用Ajax实现收藏话题，给话题投票，给回复点赞
	 */
	function handler(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		if(!(target.href&&target.href.split('?')[0]==='http://localhost/fetalk/fe-action.php')){
			return;
		}
		if(event.preventDefault){
			event.preventDefault();
		}
		else{
			event.returnValue=false;
		}
		var xhr=XHR();
		if(xhr){
			var id=target.getAttribute('id');
			var href=target.getAttribute('href');
			xhr.open('GET',href,true);
			xhr.onreadystatechange=function(){
				if(xhr.readyState===4&&xhr.status===200){	//http响应正确而且响应数据已经就绪
					var json_str=xhr.responseText;
					var json=JSON.parse(json_str);
					var message=json.message;
					var success=json.success;
					if(success===1){	//后台操作执行成功
						if(message==='collect_topic'){	//收藏话题
							target.innerHTML='取消收藏';
						}
						else if(message==='uncollect_topic'){	//取消收藏
							target.innerHTML='加入收藏';
						}
						else if(message==='vote'){		//喜欢话题
							target.innerHTML='感谢已表示';
						}
						else if(message==='agree'){		//赞同回复
							target.className+=' agree-already';
							agree_number=target.previousSibling.previousSibling;
							agree_number.innerHTML=parseInt(agree_number.innerHTML)+1;
						}
					}
				}
			};
			xhr.send(null);
		}
	}
	
	/**
	 *为分享按钮绑定点击事件，实现微博分享
	 */
	var share_btn=$('share-weibo');
	addEvent(share_btn,'click',share_weibo);
	/**
	 *事件处理程序，分享话题到新浪微博
	 */
	function share_weibo(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var weibo_url='http://service.weibo.com/share/share.php';
		var topic_url=location.href;
		var title='F2E-'+$('topic-title').innerHTML;
		var url=weibo_url+'?url='+topic_url+'&title='+title;
		var name='share_to_weibo';
		var features='width=600,height=300,'
		window.open(url,name,features);
	}
	
	/**
	 *利用事件代理为每条回复的回复按钮绑定点击事件
	 */
	var replies=$('topic-replies');
	addEvent(replies,'click',reply_to);
	
	/**
	 *reply_to 回复按钮的点击事件处理程序
	 */
	function reply_to(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var parent=target.parentNode;
		var tag=target.tagName.toLowerCase();
		if(tag==='img'&&parent.className==='reply-someone'){
			var textarea=$('reply-text');
			var author=parent.parentNode.parentNode.getElementsByTagName('a')[0].innerHTML;
			textarea.innerHTML+='@'+author;
		}
	}
</script>