<?php 
	get_header();
	$profile=user_information();
	$doc=new DOMDocument();
	foreach($profile as &$item){
		$prefix=substr($item,0,2);
		if($prefix==='<a'){
			$doc->loadHTML($item);
			$item=$doc->textContent;
		}
	}
?>
<div class="content-wrapper">
	<div class="content main" id="main">
		<h6 class="subject">用户信息设置</h6>
		<?php echo get_error_file();?>
		<div class="user-face" id="user-face">
			<span class="hint">头像</span>
			<div class="face-image">
				<img src="<?php echo fe_userFace($_SESSION['user_index'],'max');?>" />
				<img src="<?php echo fe_userFace($_SESSION['user_index'],'big');?>" />
				<img src="<?php echo fe_userFace($_SESSION['user_index']);?>" />
				<a href="fe-setting.php?set_type=face" class="set-face-btn">设置头像</a>
			</div>
		</div>
		<form action="fe-setting.php" method="post" class="setting-form" id="setting-form">
			<input type="hidden" name="csrfToken" id="csrfToken" value="<?php echo _create_token();?>" />
			<div class="form-control">
				<label for="username">用户名</label><input type="text" name="username" id="username" placeholder="<?php echo $profile['username'];?>" readonly />
				<em class="tips">用户名由字母开头，只能含有字母，数字或者下划线</em>
			</div>
			<div class="form-control">
				<label for="email">邮箱</label><input type="text" name="email" id="email" placeholder="<?php echo $profile['email'];?>" readonly />
				<em class="tips">你的email,便于登录和找回密码</em>
			</div>
			<div class="form-control">
				<label for="signature">个性签名</label><input type="text" name="signature" id="signature" value="<?php echo $profile['signature'];?>"/>
				<em class="tips">你的签名，将展示在你的个人资料页面</em>
			</div>
			<div class="form-control">
				<label for="city">城市</label><input type="text" name="city" id="city" value="<?php echo $profile['city'];?>"/>
				<em class="tips">你所在的城市，有助于后续在社区的交友</em>
			</div> 
			<div class="form-control">
				<label for="blog">博客</label><input type="text" name="blog" id="blog" value="<?php echo $profile['blog'];?>"/>
				<em class="tips">你的博客，分享你的知识</em>
			</div>
			<div class="form-control">
				<label for="company">公司</label><input type="text" name="company" id="company" value="<?php echo $profile['company'];?>"/>
				<em class="tips">你所在的公司,让朋友们更了解你一点</em>
			</div>
			<div class="form-control">
				<label for="github">Github地址</label><input type="text" name="github" id="github" value="<?php echo $profile['github'];?>"/>
				<em class="tips">你的Github用户名，用于显示你的开源项目</em>
			</div>
			<div class="form-control">
				<label for="weibo">微博个性域名</label><input type="text" name="weibo" id="weibo" value="<?php echo $profile['weibo'];?>"/>
				<em class="tips">你的微博个性域名，用于在个人资料页面显示你的微博地址</em>
			</div>
			<div class="form-control">
				<label for="douban">豆瓣个性域名</label><input type="text" name="douban" id="douban" value="<?php echo $profile['douban'];?>"/>
				<em class="tips">你的豆瓣用户名，用于在你的个人资料页面显示你正在阅读的书</em>
			</div>
			<div class="form-control">
				<label for="introduction">个人简介</label><textarea name="introduction" id="introduction"><?php echo $profile['introduction'];?></textarea>
				<em class="tips">可以稍微详细的介绍自己</em>
			</div>
			<div class="form-control">
				<input type="submit" name="update-profile" id="save-change" value="保存改动"/>
				<a href="fe-setting.php?set_type=pwd" id="set-password" class="go-link">设置密码</a>
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
	var form=$('setting-form');
	var input_tags=document.getElementsByTagName('input');
	var length=input_tags.length;
	for(var i=0;i<length;i++){
		var _this=input_tags[i];
		var type=_this.getAttribute('type');
		if(type==='text'){
			addEvent(_this,'focus',setShadow);
			addEvent(_this,'blur',setShadow);
		}
	}
	var _textarea=$('introduction');
	addEvent(_textarea,'focus',setShadow);
	addEvent(_textarea,'blur',setShadow);
	
	/**
	 *为表单提交按钮绑定点击事件
	 */
	var submit_btn=$('save-change');
	addEvent(submit_btn,'click',checkForm);
	
	/**
	 *事件处理程序，对表单进行客户端验证
	 */
	function checkForm(event){
		var event=event||window.event;
		var errorIndex=Array();
		var pattern=/^(http:\/\/|https:\/\/){1}([a-zA-Z0-9]|\.)?[a-zA-Z0-9]+\.[a-zA-Z]{2,4}[a-zA-Z\/#\?\-_=&\.]*/;
		var signature=$('signature').value;
		if(signature){	//如果填写了个性签名
			var length=signature.length;
			if(length>50){	//个性签名不要超过50个字符
				errorIndex.push(0);
			}
		}
		var city=$('city').value;
		if(city){	//如果填写了城市
			var length=city.length;
			if(length>20){	//城市名称不要超过20个字符
				errorIndex.push(1);
			}
		}
		var blog=$('blog').value;
		if(blog){	//如果填写了blog
			if(!pattern.test(blog)){	//无效的url
				errorIndex.push(4);
			}
		}
		var github=$('github').value;
		if(github){
			if(!pattern.test(github)){	//无效的url
				errorIndex.push(5);
			}
		}
		var company=$('company').value;
		if(company){
			var length=company.length;
			if(length>50){	//公司名称不要超过50个字符
				errorIndex.push(2);
			}
		}
		var weibo=$('weibo').value;
		if(weibo){
			if(!pattern.test(weibo)){	//无效的url
				errorIndex.push(6);
			}
		}
		var douban=$('douban').value;
		if(douban){
			if(!pattern.test(douban)){	//无效的url
				errorIndex.push(7);
			}
		}
		var introduction=$('introduction').value;
		if(introduction){
			var length=introduction.length;
			if(length>200){	//个人简介不要超过200个字符
				errorIndex.push(3);
			}
		}
		if(errorIndex.length>0){
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
		var form_error=['个性签名不要超过50个字符','城市名称不要超过20个字符','公司名称不要超过50个字符','个人介绍不要超过200个字符',
		'博客地址格式错误','Github地址格式错误','微博个性域名格式错误','豆瓣个性域名格式错误'];
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