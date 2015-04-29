<?php 
	get_header();
	$user_profile=user_information('other');
	$user_id=$user_profile['id'];
	$username=$user_profile['username'];
?>
<div class="content-wrapper">
	<div class="content main">
		<div class='profile'>
			<div class="profile-header">
				<div class="profile-header-left">
					<img src="<?php echo fe_userFace($user_id,'big');?>" />
					<div class="name-blog">
						<em class="name"><?php echo $username;?></em>
				<?php 
					if($user_profile['blog']){
						echo $user_profile['blog'];
					}
				?>
					</div>
				</div>
				<div class="profile-header-right">
					F2E第<?php echo $user_id;?>号成员<br/>入住于<?php echo date('Y-n-j',$user_profile['regTime']);?>
				</div>
			</div>
			<div class="profile-detail">
				<table>
		<?php 
			$map=array('username'=>'ID','city'=>'城 市','company'=>'公 司','github'=>'Github','blog'=>'Blog','douban'=>'豆 瓣','weibo'=>'微 博','signature'=>'签 名');
			$keys=array_keys($map);
			foreach($user_profile as $key=>$value):
				if($value&&in_array($key,$keys)):
		?>	
					<tr>
						<td class="key"><?php echo $map[$key];?></td>
						<td class="value"><?php echo $value;?></td?>
					</tr>
		<?php
				endif;
			endforeach;
		?>
				</table>
		<?php 
			if(_checkLogin()):
				$self_profile=user_information();
				if($user_id!=$self_profile['id'])://如果查看的是他人的主页，显示关注和私信按钮
		?>
				<div class="concern-letter" id="concern-letter">
					<a href="fe-action.php?act=concern&u=<?php echo get_user_id();?>" class="concern" title="关注他" id="concern-btn"><?php echo is_concern();?></a>
					<a href="javascript:" class="letter" title="给他发私信" id="letter-btn">私信</a>
				</div>
		<?php
				endif;
			endif;
		?>
			</div>
		</div>
		<!--倘若有开源项目，从api.github.com获取相关数据-->
	<?php 
		$topics=get_topic_number();
		if($topics>0):	//如果该用户创建过话题
			$topic_array=pagination(null,$user_id);
			$topic_list=$topic_array['list'];
	?>
		<div class="topic-list">
			<h6 class="subject">主题列表</h6>
		<?php
			foreach($topic_list as $topic_item):
		?>
			<div class="topic-item">
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
			<a href="fe-user.php?u=<?php echo $user_id;?>&c=topics&p=1" class="view-more">››查看更多主题</a>
		</div>
	<?php 
		endif;
	?>
		<!--该用户发表的回复列表-->
	<?php 
		if(get_reply_number()>0):	//如果该用户发表过回复
	?>
		<div class="reply-list">
			<h6 class="subject">回复列表</h6>
	<?php 
			$replies=extract_reply(null,$user_profile['id'])['list'];
			foreach($replies as $reply):
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
			<a href="fe-user.php?u=<?php echo $user_id;?>&c=replies&p=1" class="view-more">››查看更多回复</a>
		</div>
	<?php 
		endif;
	?>
	</div>
<?php 
	require_once('userSidebar.php');
?>
</div>
<i class="mask" id="mask"></i>
<div class="deliver-letter-box" id="deliver-letter-box">
	<div class="box-header" id="box-header">
		<h3>发送私信</h3>
		<a href="javascript:" class="close" id="close"></a>
	</div>
	<div class="input-box">
		<form class="letter-from">
			<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>" id="token"/>
			<div class="form-control">
				<label for="to-user">发给:</label><span class="to-user" id="to-user"><?php echo $username;?></span>
			</div>
			<div class="form-control">
				<label for="letter-content">内容:</label><textarea id="letter-content"></textarea>
			</div>
			<div class="form-control">
				<a href="fe-action.php?act=deliver&u=<?php echo $user_id;?>" id="deliver">发送</a> 
			</div>
		</form>
	</div>
</div>
<div class='hint' id="success-hint">
<h3>提示信息</h3>
发送成功
</div>
<script type="text/javascript">
	/**
	 *利用事件代理绑定点击事件，实现关注某人以及发送私信
	 */
	var btnDiv=$('concern-letter');
	addEvent(btnDiv,'click',handler);
	
	/**
	 *点击事件处理程序
	 */
	function handler(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var tag=target.tagName.toLowerCase();
		if(tag!='a'){
			return;
		}
		if(event.preventDefault){
			event.preventDefault();
		}
		else{
			event.returnValue=false;
		}
		var xhr=XHR();
		var id=target.getAttribute('id');
		if(id=='concern-btn'){	//点击关注按钮，关注对方或者取消关注
			var token=$('token');
			var href=target.getAttribute('href')+'&token='+token.value;
			if(xhr){
				xhr.open('GET',href,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){				//操作成功
							var message=json.message;
							if(message=='concern'){		//成功关注某人
								target.innerHTML='取消关注';
							}
							else if(message=='unconcern'){	//成功取消关注
								target.innerHTML='关注他';
							}
							var newToken=json.token;	//更新客户端的token值
							token.value=newToken;
						}
					}
				}
				xhr.send();
			}
		}
		else if(id=='letter-btn'){	//点击私信按钮，弹出私信输入框，并为输入框的相关按钮绑定点击事件
			var mask=$('mask');
			mask.style.display="block";
			letter_box.style.display='block';
		}
		
	}
	
	/**
	 *为私信输入框的头部绑定onmousedown,onmouseup事件
	 */
	var box_header=$('box-header');
	var mouseDown=false;	//标识鼠标按键的状态
	var initX,initY;		//鼠标按下时的坐标
	var offsetLeft,offsetTop;//鼠标按下去时私信输入框的偏移量
	addEvent(box_header,'mousedown',log_coordinate);
	addEvent(window,'mouseup',function(){mouseDown=false;});
	addEvent(box_header,'select')
	
	/**
	 *mousedown事件处理程序，记录鼠标坐标
	 */
	function log_coordinate(event){
		var event=event||window.event;
		initX=event.pageX;
		initY=event.pageY;
		offsetLeft=letter_box.offsetLeft;
		offsetTop=letter_box.offsetTop;
		mouseDown=true;
	}
	
	/**
	 *绑定鼠标移动事件
	 */
	var letter_box=$('deliver-letter-box');
	addEvent(window,'mousemove',drag);
	
	/**
	 *鼠标移动事件处理程序,实现私信输入框的拖拽
	 */
	var max_left=document.documentElement.clientWidth-letter_box.offsetWidth;
	function drag(event){
		var event=event||window.event;
		if(mouseDown){	//如果鼠标处于按下状态
			var currentX=event.pageX;
			var currentY=event.pageY;
			var spacingX=currentX-initX;
			var spacingY=currentY-initY;
			var left=offsetLeft+spacingX;
			var top=offsetTop+spacingY;
			if(left>=0&&left<=max_left){
				letter_box.style.left=left+'px';
			}
			if(top>=0){
				letter_box.style.top=top+'px';
			}
		}
	}
	
	var text=document.getElementById('letter-content');
	var times=0;
	var crisis_times=3; //按键次数的临界值，姑且叫它临界值吧，虽然临界值是允许的最大值，达到这个值输入域高度变化
	var line_height=15;
	
	/**
	 *为textarea绑定键盘按下事件
	 */
	addEvent(text,'keydown',control);
	
	/**
	 *键盘按键事件处理程序，实现textarea高度的伸缩
	 */
	function control(event){
		var event=event||window.event;
		var key_Code=event.keyCode;
		if(key_Code==13){	//按下回车键
				if(times<crisis_times){	
					times++;
					return;
				}
				this.style.height=parseInt(getComputedStyle(this).height)+line_height+'px';
				crisis_times++;
				times++;
		}
		else if(key_Code==38){	//按下up arrow键
			if(times>0){
				times--;
			}
		}
		else if(key_Code==40){//按下down arrow键
			if(times<crisis_times){
				times++;
			}
		}
		else if(key_Code==46){//按下delete键
			if(crisis_times>3){
				this.style.height=parseInt(getComputedStyle(this).height)-line_height+'px';
				if(crisis_times==times){
					times--;
					crisis_times--;
				}
				else if(crisis_times>times){
					crisis_times--;
				}
			}
		}
	}
	
	/**
	 *为私信输入框帮点onclick事件，实现关闭私信输入和私信发送
	 */
	addEvent(letter_box,'click',hide);
	
	/**
	 *onclick事件处理程序，最终都是隐藏私信输入框
	 */
	function hide(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var id=target.getAttribute('id');
		if(id=='close'){	//点击了关闭按钮
			var mask=$('mask');
			letter_box.style.display='none';
			mask.style.display='none';
		}
		else if(id=='deliver'){	//点击了发送按钮
			if(event.preventDefault){	//阻止事件的默认行为
				event.preventDefault();
			}
			else{
				event.returnValue=false;
			}
			var content=text.value;
			if(!content){	//没有填写私信内容
				text.setAttribute('placeholder','请填写私信内容');
				return;
			}
			else{
				var href=target.getAttribute('href');
				var uid=href.split('=')[2];
				var token=$('token')
				var xhr=XHR();
				if(xhr){
					xhr.open('POST',href,true);
					xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
					xhr.onreadystatechange=function(){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){//私信发送成功，弹出私信发送成功提示框，3秒后自动关闭
							letter_box.style.display='none';
							var hint_box=$('success-hint');
							hint_box.style.display='block';
							var timer=setTimeout(function(){
								hint_box.style.display='none';
								var mask=$('mask');
								mask.style.display='none';
							},2000);
							var newToken=json.token;
							token.value=newToken;
						}
					}
					xhr.send('user_id='+uid+'&content='+content+'&token='+token.value);
				}
			}
		}
	}
</script>
