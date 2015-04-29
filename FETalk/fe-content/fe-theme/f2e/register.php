<?php 
	get_header();
	$csrf_token=_create_token();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h3 class="subject">注册</h3>
		<?php echo get_error_file();?>
		<form action="#" method="POST" class="register-form" id="register-form">
			<input type="hidden" name="csrfToken" id="csrf_token" value="<?php echo $csrf_token;?>"/>
			<div class="form-control">
				<label for="username">用户名</label><input type="text" name="username" id="username" class="name"/>
				<em class="tips">用户名由字母开头，只能含有字母、数字或者下划线</em>
			</div>
			<div class="form-control">
				<label for="email">E-mail</label><input type="text" name="email" class="email" id="email"/>
				<em class="tips">请输入你的E-mail，便于登录和找回密码</em>
			</div>
			<div class="form-control">
				<label for="pwd">密码</label><input type="password" name="pwd" class="pwd" id="pwd"/>
				<em class="tips">密码不少于四个字符</em>
			</div>
			<div class="form-control">
				<label for="confirmPwd">密码(确认)</label><input type="password" name="confirmPwd" class="confirmPwd" id="confirmPwd"/>
				<em class="tips">请再次输入密码</em>
			</div>
			<div class="from-control form-btns">
				<input type="submit" class="form-submit btn" id="form-submit" value="注册" name="register-btn"/>
			</div>
		</form>
	</div>
	<div class="content sidebar" id="sidebar">
		<h4 class="subject">关于</h4>
		<p class="about-info">F2E是一个前端技术社区。我们试图将前端工程师解读为博学的Focus在前端领域的工程师，他不仅在前端领域颇有建树，还能在其它方面都有涉猎。加入到社区中来，参与分享，学习，不断提高吧。
		</p>
	</div>
</div>
<script type="text/javascript">
	/**
	 *为输入域绑定焦点事件
	 */
	var form=$('register-form');
	var input_tags=form.getElementsByTagName('input');
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
	 *为提交按钮绑定点击事件
	 */
	var submit_btn=$('form-submit');
	addEvent(submit_btn,'click',checkForm);
	
	/**
	 *事件处理程序，实现对注册表单的客户端检查
	 */
	function checkForm(event){
		var event=event||window.event;
		var errorIndex=Array();
		var username=$('username').value;
		if(username){	//如果填写了用户名
			var length=username.length;
			var pattern=/^[A-Za-z](([A-Za-z]|\d|_){2,11})$/;
			if(length<3){	//用户名长度少于3个字符
				errorIndex.push(1);
			}
			else if(length>12){	//用户名长度多于12个字符
				errorIndex.push(2);
			}
			else if(!pattern.test(username)){	//用户名不符合规则
				errorIndex.push(3);
			}
		}
		else{	//没有填写用户名
			errorIndex.push(0);
		}
		var email=$('email').value;
		if(email){	//如果填写了Email
			var pattern=/^[a-zA-Z0-9]+?@[a-zA-Z0-9]+?\.(com)$|(cn)$/;
			if(!pattern.test(email)){	//无效的Email
				errorIndex.push(5);
			}
		}
		else{	//没有填写Email
			errorIndex.push(4);
		}
		var password=$('pwd').value;
		if(password){
			var confirmPwd=$('confirmPwd').value;
			var length=password.length;
			if(length<6){	//密码长度少于6个字符
				errorIndex.push(6);
			}
			else if(confirmPwd!==password){	//两次密码输入不一致
				errorIndex.push(7);
			}
		}
		else{	//没有填写密码
			errorIndex.push(8);
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
		var form_error=['必须填写用户名','用户名长度过短(3-12个字符)','用户名长度过长(3-12个字符)','用户名格式错误(字母开头，由字母，数字，下划线构成)','必须填写Email','无效的Email','密码长度过短','两次密码输入不一致','必须填写密码'];
		var ul=document.createElement('ul');
		ul.setAttribute('id','form-error');
		var length=arguments.length;
		for(var i=0;i<length;i++){
			var li=document.createElement('li');
			var text=document.createTextNode(form_error[arguments[i]]);
			li.appendChild(text);
			ul.appendChild(li);
		}
		var form=$('register-form');
		main.insertBefore(ul,form);
	}
</script>
</body>