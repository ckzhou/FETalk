<?php 
	get_header();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h6 class="subject">用户密码设置</h6>
		<?php echo get_error_file();?>
		<form class="password-form" method="POST" action="fe-setting.php" id="pwd-form">
			<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>" />
			<div class="form-control">
				<label for="current-password">当前密码</label><input type="password" name="current-password" id="current-password" />
				<em class="tips">密码不少于6个字符</em>
			</div>
			<div class="form-control">
				<label for="new-password">新密码</label><input type="password" name="new-password" id="new-password" />
				<em class="tips">密码不少于6个字符</em>
			</div>
			<div class="form-control">
				<label for="confirm-password">确认密码</label><input type="password" name="confirm-password" id="confirm-password" />
				<em class="tips">请再次输入密码</em>
			</div>
			<div class="form-control">
				<input type="submit" name="update-pwd" id="save-change" value="保存改动"/>
				<a href="fe-setting.php" class="go-link">返回设置页</a>
			</div>
		</form>
	</div>
<?php 
	require_once('setSidebar.php');
?>
</div>
<script type="text/javascript">
	/**
	 *为输入域绑定焦点事件
	 */
	var form=$('pwd-form');
	var input_tags=document.getElementsByTagName('input');
	var length=input_tags.length;
	for(var i=0;i<length;i++){
		var _this=input_tags[i];
		var type=_this.getAttribute('type');
		if(type==='password'){
			addEvent(_this,'focus',setShadow);
			addEvent(_this,'blur',setShadow);
		}
	}
	
	/**
	 *为提交按钮绑定点击事件
	 */
	var submit_btn=$('save-change');
	addEvent(submit_btn,'click',checkForm);
	
	/**
	 *事件处理程序，对表单进行客户端检验
	 */
	function checkForm(event){
		var curPwd=$('current-password').value;
		var errorIndex=Array();
		if(curPwd){	//填写了当前密码
			var length=curPwd.length;
			if(length<6){	//密码长度少于6个字符
				errorIndex.push(0);
			}
		}
		else{	//没有填写当前密码
			errorIndex.push(2);
		}
		var newPwd=$('new-password').value;
		if(newPwd){	//填写了新密码
			var cfmPwd=$('confirm-password').value;
			var length=cfmPwd.length;
			if(length<6){	//密码长度少于6个字符
				errorIndex.push(0);
			}
			else if(cfmPwd!==newPwd){	//两次密码输入不一致
				errorIndex.push(1);
			}
		}
		else{	//没有填写新密码
			errorIndex.push(3);
		}
		if(errorIndex.length>0){	//如果前面检验出了错误
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
		var form_error=['密码长度过短','两次密码输入不一致','必须填写当前密码','必须填写新密码'];
		var ul=document.createElement('ul');
		ul.setAttribute('id','form-error');
		var length=arguments.length;
		for(var i=0;i<length;i++){
			var li=document.createElement('li');
			var text=document.createTextNode(form_error[arguments[i]]);
			li.appendChild(text);
			ul.appendChild(li);
		}
		var form=$('pwd-form');
		main.insertBefore(ul,form);
	}
</script>