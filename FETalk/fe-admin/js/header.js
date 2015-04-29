/**
 *为头部选项绑定mouseover和mouseout事件
 */
var option_list=$('parent-options-list');
var option_items=option_list.getElementsByTagName('li');
var length=option_items.length;
for(var i=0;i<length;i++){
	var _this=option_items[i];
	var id=_this.id;
	if(id.indexOf('parent-option')===0){
		addEvent(_this,'mouseover',ctrSubOption);
		addEvent(_this,'mouseout',ctrSubOption);
	}
}

/**
 *事件处理程序，实现子选项的展现与隐藏
 */
function ctrSubOption(event){
	var event=event||window.event;
	var text=this.getElementsByTagName('a')[0];
	var subOption=this.getElementsByTagName('ul')[0];
	var eventType=event.type;
	if(eventType==='mouseover'){	//触发mouseover事件
		subOption.style.display='block';
		text.style.color='#6E6E6E';
		text.style.backgroundColor='#FFF';
	}
	else if(eventType==='mouseout'){	//触发mouseout事件
		subOption.style.display='none';
		text.style.color='#D1D1D1';
		text.style.backgroundColor='#4B4B4B';
	}
}

/**
 *为网页头部右边的个人信息区域绑定mouseover和mouseout事件
 */
var userBox=$('user-info');
addEvent(userBox,'mouseover',ctrUserBox);
addEvent(userBox,'mouseout',ctrUserBox);

/**
 *事件处理程序，实现管理员信息区域的展现于隐藏
 */
function ctrUserBox(event){
	var event=event||window.event;
	var text=$('account-option');
	var user_box=$('detail-user-info');
	var eventType=event.type;
	if(eventType==='mouseover'){	//触发mouseover事件
		text.style.color='#6E6E6E';
		text.style.backgroundColor="#FFF";
		user_box.style.display='block';
	}
	else if(eventType==='mouseout'){	//触发mouseout事件
		text.style.color='#D1D1D1';
		text.style.backgroundColor='#4B4B4B';
		user_box.style.display='none';
	}
	
}
