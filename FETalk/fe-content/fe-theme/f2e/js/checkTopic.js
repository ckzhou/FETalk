var title=$('create-title');
var text=$('create-text');
/**
 *事件处理程序，当输入域获得焦点的时候添加边框外阴影
 */
function setShadow(event){
	var event=event||window.event;
	var eventType=event.type;
	if(eventType==='focus'){
		this.style.borderColor='#5BCAFF';
		this.style.boxShadow='0 0 10px #ACE4FF';
	}
	else if(eventType==='blur'){
		this.style.borderColor='#F7F7F7';
		this.style.boxShadow='inset 0 0 5px #A5A5A5';
	}
}

/**
 *为输入域绑定focus和blur事件
 */
addEvent(title,'focus',setShadow);
addEvent(title,'blur',setShadow);
addEvent(text,'focus',setShadow);
addEvent(text,'blur',setShadow);

/**
 *当表单验证失败，插入相关错误信息
 */
function getFormError(){
	var ul=$('form-error');
	var main=$('main');
	if(ul){	//先删除旧的错误信息
		main.removeChild(ul);
	}
	var error_array=['请填写帖子标题','请填写帖子内容','帖子标题长度过短(3-56个字符)','帖子标题长度过长(3-56个字符)','帖子内容长度过短(少于15个字符)'];
	var ul=document.createElement('ul');
	ul.setAttribute('id','form-error');
	var len_param=arguments.length;
	for(var i=0;i<len_param;i++){
		var li=document.createElement('li');
		var item=document.createTextNode(error_array[arguments[i]]);
		li.appendChild(item);
		ul.appendChild(li);
	}
	var form=$('create-form');
	main.insertBefore(ul,form);
}

/**
 *事件处理程序，实现对话题创建表单的验证
 */
function checkForm(event){
	var result=true;	//表单填写正确与否的标识
	var len_title=title.value.length;
	var len_text=text.value.length;
	if(!len_title&&!len_text){	//如果帖子标题和帖子内容都没有填写
		getFormError(0,1);
		result=false;
	}
	else if(!len_title&&len_text){	//如果帖子标题没有填写
		if(len_text<15){	//如果帖子内容少于15个字符
			getFormError(0,4);
		}
		else{
			getFormError(0);
		}
		result=false;
	}
	else if(len_title&&!len_text){	//如果帖子内容没有填写
		if(len_title<3){	//如果帖子标题少于3个字符
			getFormError(1,2);
		}
		else if(len_title>56){	//如果帖子标题多于56个字符
			getFormError(1,3);
		}
		else{
			getFormError(1);
		}
		result=false;
	}
	else{	//如果每一项都填写了
		if(len_title<3){	//帖子标题少于3个字符
			getFormError(2);
			result=false;
		}
		if(len_title>56){	//帖子标题多于56个字符
			getFormError(3);
			result=false;
		}
		if(len_text<15){	//帖子内容少于15个字符
			getFormError(4);
			result=false;
		}
	}
	if(!result){	//表单填写错误，阻止表单提交
		var event=event||window.event;
		try{
			event.preventDefault();
		}
		catch(e){
			event.returnValue=false;
		}
	}
}

/**
 *为表单提交按钮绑定点击事件
 */
var btn=$('create-btn');
addEvent(btn,'click',checkForm);