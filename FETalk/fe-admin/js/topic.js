/**
 *美化分类表格
 */
var table=$('topicTable');
drawTable(table);

/**
 *为搜索按钮绑定点击事件
 */
var search_btn=$('submit');
addEvent(search_btn,'click',searchTopic);

/**
 *事件处理程序，按照标题关键字对帖子进行搜索并且展现
 */
function searchTopic(event){
	var event=event||window.event;
	if(event.preventDefault){	//阻止事件默认行为
		event.preventDefault();
	}
	else{
		event.returnValue=false;
	}
	//检查是否已键入帖子标题关键字
	var keyWord=$('key-word');
	if(!keyWord.value){	//没有填写任何关键字
		keyWord.style.borderColor='#f90000';
		return;
	}
	var xhr=XHR();
	if(xhr){	//XMLHttpRequest对象创建成功
		var href='logic.php?act=searchTopic&title='+keyWord.value;
		xhr.open('GET',href,true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var hint=$('table-hint');
				hint.innerHTML='搜索结果';
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				if(success==1){	//搜索到了相关结果
					var result=json.result;
					var length=result.length;
					var template=$('template');
					var tbody=table.getElementsByTagName('tbody')[0];
					tbody.innerHTML=null;
					for(var i=0;i<length;i++){
						var _this=result[i];
						var tr=document.createElement('tr');
						tr.innerHTML=template.innerHTML;
						var td=tr.getElementsByTagName('td');
						td[0].innerHTML=_this.title;
						td[0].setAttribute('title',_this.title);
						td[1].innerHTML=_this.node;
						td[2].innerHTML=_this.author;
						td[3].innerHTML=_this.time;
						tbody.appendChild(tr);
					}
				}	
				else if(success==0){	//没有相关匹配的结果
					var none=$('no-result');
					var tbody=table.getElementsByTagName('tbody')[0];
					tbody.innerHTML=null;
					tbody.appendChild(none.cloneNode(true));
				}
				controlMenubar();
				drawTable(table);
			}
		}
		xhr.send(null);
	}
}

/**
 *为Table绑定点击事件
 */
addEvent(table,'click',deleteTopic);

/**
 *事件处理程序，实现对垃圾帖子的删除
 */
function deleteTopic(event){
	var event=event||window.event;
	var target=event.target||event.srcElement;
	var clsName=target.className;
	if(clsName==='delete'){	//如果点击的是删除按钮
		var prompt=confirm('确认这是一篇垃圾帖并且删除它吗？确认后有关此贴的任何信息都将被删除！');	//弹出确认框
		if(prompt){	//确认删除
			var tr=target.parentNode.parentNode;
			var topic=tr.getElementsByTagName('td')[0].innerHTML;
			var xhr=XHR();
			if(xhr){
				var href='logic.php?act=deleteTopic&topic='+topic;
				xhr.open('GET',href,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){	//删除帖子成功
							var tbody=table.getElementsByTagName('tbody')[0];
							tbody.removeChild(tr);
							controlMenubar();
						}
					}
				}
				xhr.send(null);
			}
		}
	}
}
