/**
 *美化表格
 */
var table=$('userTable');
drawTable(table);

/**
 *为搜索按钮绑定点击事件
 */
var search_btn=$('submit');
addEvent(search_btn,'click',searchUser);

/**
 *事件处理程序，按照关键字搜索相关用户
 */
function searchUser(event){
	var event=event||window.event;
	if(event.preventDefault){
		event.preventDefault();
	}	
	else{
		event.returnValue=false;
	}
	//检查是否输入了关键字
	var keyWord=$('key-word');
	if(!keyWord.value){
		keyWord.style.borderColor='#f80000';
		return;
	}
	var xhr=XHR();
	if(xhr){
		var href='logic.php?act=searchUser&user='+keyWord.value;
		xhr.open('GET',href,true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var hint=$('table-hint');
				hint.innerHTML='搜索结果';
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				if(success==1){	//搜索到相关用户
					var template=$('template');
					var tbody=table.getElementsByTagName('tbody')[0];
					tbody.innerHTML=null;
					var result=json.result;
					var length=result.length;
					for(var i=0;i<length;i++){
						var _this=result[i];
						var tr=document.createElement('tr');
						tr.innerHTML=template.innerHTML;
						var td=tr.getElementsByTagName('td');
						td[0].innerHTML=_this.username;
						td[1].innerHTML=_this.email;
						td[2].innerHTML=_this.time;
						td[3].innerHTML=_this.number;
						tbody.appendChild(tr);
					}
				}
				else if(success==0){	//没有匹配的相关用户
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
addEvent(table,'click',defriend);

/**
 *事件处理程序，拉黑不守规则的用户或解除拉黑
 */
function defriend(event){
	var event=event||window.event;
	var target=event.target||event.srcElement;
	var clsName=target.className;
	if(clsName==='defriend'||clsName==='relive'){	//点击了拉黑按钮或解除按钮
		var xhr=XHR();
		if(xhr){
			var email=target.parentNode.parentNode.getElementsByTagName('td')[1].innerHTML;
			var href='logic.php?act='+clsName+'&email='+email;
			xhr.open('GET',href,true);
			xhr.onreadystatechange=function(){
				if(xhr.readyState==4&&xhr.status==200){	
					var json_str=xhr.responseText;
					var json=JSON.parse(json_str);
					var success=json.success;
					if(success==1){	//操作成功
						if(clsName==='defriend'){
							target.innerHTML='解除';
							target.className='relive';
						}
						else if(clsName==='relive'){
							target.innerHTML='关闭';
							target.className='defriend';
						}
					}
				}
			}
			xhr.send(null);
		}
	}
}