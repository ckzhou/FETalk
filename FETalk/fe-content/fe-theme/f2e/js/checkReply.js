/** 
 *当输入域获得焦点的时候给边框添加外阴影
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
var replyText=$('reply-text');
addEvent(replyText,'focus',setShadow);
addEvent(replyText,'blur',setShadow);

/**
 *客户端验证回复表单
 */
var btn=$('reply-btn');
addEvent(btn,'click',checkForm);

/**
 *表单验证函数
 */
function checkForm(event){
	var reply_box=$('reply-text');
	var reply_text=reply_box.value;
	if(!reply_text){	//如果没有填写回复内容
		var main=$('reply-box')||$('main');
		var ul=$('form-error');
		if(ul){
			main.removeChild(ul);
		}
		var ul=document.createElement('ul');
		ul.setAttribute('id','form-error');
		var li=document.createElement('li');
		var item=document.createTextNode('请填写回复内容');
		li.appendChild(item);
		ul.appendChild(li);
		var form=$('reply-form');
		main.insertBefore(ul,form);
		var event=event||window.event;
		if(event.preventDefault){	//如果表单验证失败，阻止事件默认行为
			event.preventDefault();
		}
		else{
			event.returnValue=false;
		}
	}
}