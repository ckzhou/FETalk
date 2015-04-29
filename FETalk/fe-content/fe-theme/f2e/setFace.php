<?php 
	get_header();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h6 class="subject">用户头像设置</h6>
		<?php echo get_error_file();?>
		<div class="user-face" id="user-face">
			<span class="hint">头像</span>
			<div class="face-image">
				<img src="<?php echo fe_userFace($_SESSION['user_index'],'max');?>" />
				<img src="<?php echo fe_userFace($_SESSION['user_index'],'big');?>" />
				<img src="<?php echo fe_userFace($_SESSION['user_index']);?>" />
			</div>
		</div>
		<form class="face-form" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>" />
			<div class="form-control">
				<label for="upload">上传</label><input type="file" name="face" class="face" id="avatar"/>
				<em class="tips">请选择要上传的头像文件&nbsp;&nbsp;(JPEG，PNG，GIF，文件大小不要超过1M)</em>
			</div>
			<div class="form-control">
				<input type="submit" name="update-face" id="save-change" value="保存改动"/>
				<a href="" class='go-link'>从Gravatar获取</a>
				<a href="fe-setting.php" class="go-link">返回设置页</a>
			</div>
		</form>
	</div>
	<?php require_once('setSidebar.php');?>
</div>
<script type="text/javascript">
	/**
	 *为提交按钮绑定点击事件
	 */
	var submit_btn=$('save-change');
	addEvent(submit_btn,'click',checkForm);
	
	/**
	 *事件处理程序，对表单提交进行客户端验证
	 */
	function checkForm(event){
		var event=event||window.event;
		errorIndex=Array();
		var avatar=$('avatar').value;
		if(avatar){	//如果选择了文件，验证文件扩展名
			var tmpArr=avatar.split('.');
			var index=tmpArr.length-1;
			var extension=tmpArr[index].toLowerCase();
			var allow_ext=['jpg','png','gif'];
			if(allow_ext.indexOf(extension)===-1){	//如果选择了错误的文件
				errorIndex.push(0);
			}
		}
		else{	//没有选择任何文件
			errorIndex.push(1);
		}
		if(errorIndex.length>0){	//前面检验出了错误
			if(event.preventDefault){
				event.preventDefault();
			}
			else{
				event.returnValue=false;
			}
			getFormError.apply(this,errorIndex);
		}
	}
	
	/**
	 *生成错误信息列表
	 */
	function getFormError(){
		var ul=$('form-error');
		var main=$('main');
		if(ul){
			main.removeChild(ul);
		}
		var form_error=['请选择正确的图片文件(JPEG，PNG，GIF图片文件)','请先选择要上传的头像'];
		var ul=document.createElement('ul');
		ul.setAttribute('id','form-error');
		var length=arguments.length;
		for(var i=0;i<length;i++){
			var li=document.createElement('li');
			var text=document.createTextNode(form_error[arguments[i]]);
			li.appendChild(text);
			ul.appendChild(li);
		}
		var face=$('user-face');
		main.insertBefore(ul,face);
	}
</script>