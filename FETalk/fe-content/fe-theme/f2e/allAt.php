<?php
	/**
	 *呈现所有@你的历史记录
	 */
	get_header();
	$at=get_all_at();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<div class="mention-list">
			<h6 class="subject">F2E>历史记录>@我的</h6>
			<span id="token" style="display:none"><?php echo _create_token();?></span>
<?php
	$at_number=$at['number'];
	if($at_number>0):
		$at_items=$at['items'];
		foreach($at_items as $item):
			if(!isset($item['reply_text'])):
?>
				<p class="at_item"><a href="fe-user.php?u=<?php echo $item['from_uid'];?>"><?php echo $item['from_user'];?></a>
				在发表话题<a href="fe-topic.php?t=<?php echo $item['topic_id'];?>"><?php echo $item['topic_title'];?></a>时@了你
				<a href="fe-action.php?act=delAt&a=<?php echo $item['id'];?>" class="del-at">删除</a>
				</p>
				<div class="text"><?php echo $item['topic_text'];?></div>
		<?php 
			else:
		?>
				<p class="at_item"><a href="fe-user.php?u=<?php echo $item['from_uid'];?>"><?php echo $item['from_user'];?></a>
				在回复话题<a href="fe-topic.php?t=<?php echo $item['topic_id'];?>"><?php echo $item['topic_title'];?></a>时@了你
				<a href="fe-action.php?act=delAt&a=<?php echo $item['id'];?>" class="del-at">删除</a>
				</p>
				<div class="text"><?php echo $item['reply_text'];?></div>
<?php
			endif;
		endforeach;
	else:
?>
		<p class='no-mention'>您暂时还没有@信息</p>
<?php 
	endif;
?>
		</div>
	</div>
</div>
<script type="text/javascript">
	/**
	 *利用事件代理为删除链接绑定点击事件
	 */
	var mainDiv=$('main');
	addEvent(mainDiv,'click',delAt);
	
	/**
	 *点击事件处理程序，删除at历史记录
	 */
	function delAt(event){
		var event=event||window.event;
		var target=event.target||event.srcElement;
		var clsName=target.getAttribute('class');
		if(clsName=='del-at'){	//如果点击的是删除链接
			if(event.preventDefault){	//阻止事件的默认行为
				event.preventDefault();
			}
			else{
				event.returnValue=false;
			}
			var xhr=XHR();
			if(xhr){
				var token=$('token').innerHTML;
				var href=target.getAttribute('href')+'&token='+token;
				xhr.open("GET",href,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){	//如果删除at记录成功
							location.reload();	//重载页面
						}
					}
				}
				xhr.send(null);
			}
		}
	}
</script>