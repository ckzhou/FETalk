/* /**
 *显示当前页面对应的菜单项的子菜单
 */
var href=location.href;
var components=href.split('/');
var max_index=components.length-1;
var current=components[max_index].split('.')[0];

/**
 *为菜单项绑定mouseover和mouseout事件
 */
var menu=$('menu');
var items=menu.getElementsByTagName('li');
var length=items.length;
for(var i=0;i<length;i++){
	var _this=items[i];
	var clsName=_this.className;
	if(clsName.indexOf('menu-list-item')===0){	//过滤掉子菜单中的li元素
		if(clsName.split(' ')[1]===current){	//当前页面对应的菜单项
			_this.setAttribute('id','current');
			var icon=_this.getElementsByTagName('span')[0];
			icon.style.backgroundPosition=getComputedStyle(icon)['backgroundPosition'].replace(/-34px/,'-2px');
		}
		else{
			addEvent(_this,'mouseover',ctrSubMenu);
			addEvent(_this,'mouseout',ctrSubMenu);
		}
		
	}
}

/**
 *事件处理程序，实现子菜单的展现与隐藏
 */
function ctrSubMenu(event){
	var event=event||window.event;
	var target=event.currentTarget;	//事件冒泡过程中捕捉菜单项li元素
	var id=target.getAttribute('id');
	if(id=='current'){	//如果是当前页面所对应的菜单项，退出函数
		return;
	}
	var eventType=event.type;
	var icon=target.getElementsByTagName('span')[0];
	var text=target.getElementsByTagName('a')[0];
	var arrow=target.getElementsByTagName('div')[0];
	var subMenu=target.getElementsByTagName('ul')[0];
	if(eventType=='mouseover'){	//onmouseover事件触发
		icon.style.backgroundPosition=getComputedStyle(icon)['backgroundPosition'].replace(/-34px/,'-2px');
		text.style.color='#FF5151';
		text.style.backgroundColor='#C0C0C0';
		arrow.style.display='block';
		subMenu.style.display='block';
	}
	else if(eventType=='mouseout'){	//onmouseout事件触发
		icon.style.backgroundPosition=getComputedStyle(icon)['backgroundPosition'].replace(/-2px/,'-34px');
		text.style.color='#006DA2';
		text.style.backgroundColor='transparent';
		arrow.style.display='none';
		subMenu.style.display='none';
	}
}