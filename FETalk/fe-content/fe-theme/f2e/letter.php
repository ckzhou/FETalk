<?php 
	/**
	 *呈现用户所有的私信对话记录
	 */
	 get_header();
	 $dialogue=get_all_letter();
	 $self_id=get_user_id(null,true);
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<div class="dialogue-list">
			<h6 class="subject">F2E>历史记录>私信</h6>
<?php 
	if(is_array($dialogue)):
		foreach($dialogue as $item):
			$letter_number=count($item);
			$latest_letter=array_shift($item);
?>
			<div class="letter">
		<?php 
			if(isset($latest_letter['from_uid'])):
				$others_id=$latest_letter['from_uid'];
				$username=get_username($others_id);
		?>
				<a href="fe-user.php?u=<?php echo $others_id;?>" class="small-face"><img src="<?php echo fe_userFace($others_id,'small');?>" /></a>
				<div class="letter-content">
					<a href="fe-user.php?u=<?php echo $others_id;?>"><?php echo $username;?></a>发送给我
		<?php
			else:
				$others_id=$latest_letter['to_uid'];
				$username=get_username($others_id);
		?>
				<a href="fe-user.php?u=<?php echo $self_id;?>" class="small-face"><img src="<?php echo fe_userFace();?>" /></a>
				<div class="letter-content">
					我发送给<a href="fe-user.php?u=<?php echo $others_id;?>"><?php echo $username;?></a>:
		<?php
			endif;
		?>
					<div class="letter-text"><?php echo $latest_letter['content'];?></div>
					<div class="meta">
						<span class="time"><?php echo format_time($latest_letter['cTime']);?></span>
						<span class="manipulate">
							<a href="fe-mention.php?c=dialogue&f=<?php echo $others_id;?>">共<?php echo $letter_number;?>条对话</a>
							<a href="fe-action.php?act=deliver&u=<?php echo $others_id;?>&name=<?php echo $username;?>" class="reply">回复</a>
							<a href="fe-action.php?act=delDialogue&u=<?php echo $others_id;?>" class="delete">删除</a>
						</span>
					</div>
				</div>
			</div>
<?php
		endforeach;
	else:
?>
		<p class='no-mention'>您暂时还没收到新的私信消息</p>
<?php 
	endif;
?>
		</div>
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
			<input type="hidden" name="token" id="token" value="<?php echo _create_token();?>"/>
			<div class="form-control">
				<label for="to-user">发给:</label><span class="to-user" id="to-user"></span>
			</div>
			<div class="form-control">
				<label for="letter-content">内容:</label><textarea id="letter-content"></textarea>
			</div>
			<div class="form-control">
				<a href="javascript:" id="deliver">发送</a> 
			</div>
		</form>
	</div>
</div>
<div class="hint" id="delete-hint">
<h3>删除私信对话<a href="javascript:" id="cancel"></a></h3>
确认删除？
<a href="javascript:" id="sure">确定</a>
</div>
<script type="text/javascript">
	/**
	 *利用事件代理为所有的回复链接和删除链接绑定点击事件
	 */
	var letter_box=$('deliver-letter-box');
	var hint_box=$('delete-hint');
	var reply_href;
	var del_href;
	var mainDiv=$('main');
	addEvent(mainDiv,'click',manipulate);
	
	/**
	 *点击事件处理程序，弹出私信输入框和删除提示框
	 */
	function manipulate(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var clsName=target.getAttribute('class');
		if(clsName=='reply'||clsName=='delete'){
			if(event.preventDefault){
				event.preventDefault();
			}
			else{
				event.returnValue=false;
			}
		}
		if(clsName=='reply'){	//点击了回复链接,弹出私信输入框
			reply_href=target.getAttribute('href');
			var name=reply_href.split('=')[3];
			var receiver=$('to-user');
			receiver.innerHTML=name;
			var mask=$('mask');
			mask.style.display='block';
			letter_box.style.display='block';
		}
		else if(clsName=='delete'){	//点击了删除链接，弹出确定删除提示
			del_href=target.getAttribute('href');
			hint_box.style.display='block';
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
			var content=text.value;
			if(!content){	//如果没有填写私信内容
				text.setAttribute('placeholder','请填写私信内容');
				return;
			}
			else{
				var uid=reply_href.split('=')[2];
				var token=$('token').value;
				var xhr=XHR();
				if(xhr){
					xhr.open('POST',reply_href,true);
					xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
					xhr.onreadystatechange=function(){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){//私信发送成功，重载页面
							letter_box.style.display='none';
							location.reload();
						}
					}
					xhr.send('user_id='+uid+'&content='+content+'&token='+token);
				}
			}
		}
	}
	
	/**
	 *为删除提示框绑定点击事件
	 */
	addEvent(hint_box,'click',delDialogue);
	/**
	 *点击事件处理程序，删除该对话下所有的私信记录
	 */
	function delDialogue(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var id=target.getAttribute('id');
		if(id=='cancel'){	//如果点击了关闭按钮
			hint_box.style.display='none';
		}
		else if(id=='sure'){	//如果点击了确定按钮
			var xhr=XHR();
			var token=$('token').value;
			if(xhr){
				xhr.open('GET',del_href+'&token='+token,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						console.log(success);
						if(success==1){	//删除成功
							location.reload();
						}
					}
				}
				xhr.send(null);
			}
		}
	}
</script>