<?php 
	get_header();
	$csrfToken=_create_token();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h3 class="subject">登录</h3>
		<?php echo get_error_file();?>
		<form action="fe-login.php" method="POST" class="login-form" id="login-form">
			<input type="hidden" name="csrfToken" id="csrf_token" value="<?php echo $csrfToken;?>"/>
			<div class="form-control">
				<label for="email">E-mail</label><input type="text" name="email" class="email" id="email"/>
				<em class="tips">请输入您注册时候的邮箱</em>
			</div>
			<div class="form-control">
				<label for="pwd">密码</label><input type="password" name="pwd" class="pwd" id="pwd"/>
				<em class="tips">请输入密码(不少于四个字符)</em>
			</div>
			<div class="from-control form-btns">
				<input type="submit" class="form-submit btn" id="form-submit" value="登录" name="login-btn"/>
				<a href="fe-register.php" class="new-account btn">注册账号</a>
				<a href="fe-forgot.php" class="forgot-pwd btn">忘记密码了</a>
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
	var form=$('login-form');
	var input_tags=form.getElementsByTagName('input');
	console.log(input_tags);
	var length=input_tags.length;
	for(var i=0;i<length;i++){
		var _this=input_tags[i];
		var type=_this.getAttribute('type');
		if(type==='text'||type==='password'){
			addEvent(_this,'focus',setShadow);
			addEvent(_this,'blur',setShadow);
		}
	}
	
	/**
	 *给表单提交按钮绑定点击事件
	 */
	var submit_btn=$('form-submit');
	addEvent(submit_btn,'click',checkForm);
	
	/**
	 *点击事件处理程序，实现表单的客户端验证
	 */
	function checkForm(event){
		var event=event||window.event;
		var errorIndex=Array();
		var email=$('email').value;
		if(email){	//如果填写了email
			var pattern=/^[a-zA-Z0-9]+?@[a-zA-Z0-9]+?\.(com)$|(cn)$/;
			if(!pattern.test(email)){	//无效的email格式
				errorIndex.push(1);
			}
		}
		else{	//如果没有填写email
			errorIndex.push(0);
		}
		var password=$('pwd').value;
		if(password){	//如果填写了密码
			var length=password.length;
			if(length<6){	//如果密码长度少于6个字符
				errorIndex.push(3);
			}
		}
		else{	//如果没有填写密码
			errorIndex.push(2);
		}
		if(errorIndex.length>0){	//前面检验出了表单填写错误
			if(event.preventDefault){	//阻止事件默认行为
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
		var form_error=['必须填写Email','无效的Email','必须要填写密码','密码长度过短(6-15个字符)'];
		var ul=document.createElement('ul');
		ul.setAttribute('id','form-error');
		var length=arguments.length;
		for(var i=0;i<length;i++){
			var li=document.createElement('li');
			var text=document.createTextNode(form_error[arguments[i]]);
			li.appendChild(text);
			ul.appendChild(li);
		}
		var form=$('login-form');
		main.insertBefore(ul,form);
	}
</script>