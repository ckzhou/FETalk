<?php 
	/**
	 *密码找回页面
	 */
	get_header();
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h3 class="subject">找回密码</h3>
		<?php echo get_error_file();?>
		<form action="fe-forgot.php" method="POST" class="forgot-form" id="forgot-form">
			<input type="hidden" name="csrfToken" id="csrf_token" value="<?php echo _create_token();?>"/>
			<div class="form-control">
				<label for="username">用户名</label><input type="text" name="username" class="name" id="username"/>
				<em class="tips">用户名由字母开头，只能含有字母、数字或者下划线</em>
			</div>
			<div class="form-control">
				<label for="email">Email</label><input type="text" name="email" class="email" id="email"/>
				<em class="tips">请输入你的E-mail，新密码将发送到这里</em>
			</div>
			<div class="from-control form-btns">
				<input type="submit" class="form-submit btn" id="form-submit" value="找回密码" name="retrieve-btn"/>
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
	var form=$('forgot-form');
	var input_tags=form.getElementsByTagName('input');
	var length=input_tags.length;
	for(var i=0;i<length;i++){
		var _this=input_tags[i];
		var type=_this.getAttribute('type');
		if(type==='text'){
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
	 *点击事件处理程序，实现表单的客户端验证
	 */
	function checkForm(event){
		var event=event||window.event;
		var errorIndex=Array();
		var email=$('email').value;
		var username=$('username').value;
		if(username){	//如果填写了用户名
			var length=username.length;
			var pattern=/^[A-Za-z](([A-Za-z]|\d|_){2,11})$/;
			if(!pattern.test(username)){	//用户名不符合规则
				errorIndex.push(3);
			}
		}
		else{
			errorIndex.push(0);
		}
		if(email){	//如果填写了email
			var pattern=/^[a-zA-Z0-9]+?@[a-zA-Z0-9]+?\.(com)$|(cn)$/;
			if(!pattern.test(email)){	//无效的email格式
				errorIndex.push(2);
			}
		}
		else{	//如果没有填写email
			errorIndex.push(1);
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
		var form_error=['必须填写用户名','必须填写Email','无效的Email','用户名不符合规则(由字母开头，只能包含字母，数字或者下划线)'];
		var ul=document.createElement('ul');
		ul.setAttribute('id','form-error');
		var length=arguments.length;
		for(var i=0;i<length;i++){
			var li=document.createElement('li');
			var text=document.createTextNode(form_error[arguments[i]]);
			li.appendChild(text);
			ul.appendChild(li);
		}
		var form=$('forgot-form');
		main.insertBefore(ul,form);
	}
</script>