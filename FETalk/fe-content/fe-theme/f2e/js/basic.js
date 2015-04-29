/**
 *通过id获取元素节点
 */
function $(id){
	return document.getElementById(id);
}

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

/**
 *事件绑定
 */
function addEvent(element,eventType,handler){
	if(element.addEventListener){
		element.addEventListener(eventType,handler,false);
	}
	else if(element.attachEvent){
		element.attachEvent("on"+eventType,handler);
	}
	else{	
		element['on'+eventType]=handler;
	}
}

/**
 *搜索框宽度扩展
 */
function expandSearchBox(){
	var width=searchBox.offsetWidth-7;
	var expandSpeed=Math.ceil((finalWidth-width)*0.6);
	if(width<finalWidth){
		searchBox.style.width=(width+expandSpeed)+"px";
		var timer=setTimeout(expandSearchBox,30);
	}
}

/**
 *搜索框宽度缩减
 */
function shortenSearchBox(){
	var width=searchBox.offsetWidth-7;
	var shortenSpeed=Math.ceil((width-initialWidth)*0.6);
	if(width>initialWidth){
		searchBox.style.width=(width-shortenSpeed)+"px";
		var timer=setTimeout(shortenSearchBox,30);
	}
}


/**
 *生成XMLHTTPRequest对象
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

