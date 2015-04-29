/**
 *通过id获取元素节点的函数
 */
function $(id){
	return document.getElementById(id);
}

/**
 *为元素绑定事件的函数
 */
function addEvent(element,eventType,handler){
	if(element.addEventListener){
		element.addEventListener(eventType,handler,false);
	}
	else if(element.attachEvent){
		element.attachEvent('on'+eventType,handler);
	}
	else{
		element['on'+eventType]=handler;
	}
}

/**
 *创建XMLHttpRequest对象
 */
function XHR(){
	var xhr;
	if(window.XMLHttpRequest){
		xhr=new XMLHttpRequest();
	}
	else if(window.ActiveXObject){
		xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}
	else{
		return null;
	}
	return xhr;
}

/**
 *美化表单的函数
 */
function drawTable(table){
	var tr=table.getElementsByTagName('tr');
	var length=tr.length;
	for(var i=1;i<length;i++){
		var _this=tr[i];
		if(i%2==0){	//给奇数行加上背景颜色
			_this.style.backgroundColor='#f6f6f6';
		}
		else{
			_this.style.backgroundColor='#fff';
		}
	}
}