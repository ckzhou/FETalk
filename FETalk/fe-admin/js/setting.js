/**
 *美化表格
 */
var table=$('pageTable');
drawTable(table);

/**
 *为内容区域绑定点击事件
 */
var wrapper=$('contentWrapper');
addEvent(wrapper,'click',setSite);

/**
 *事件处理函数，实现对网站的基本设置
 */
function setSite(event){
	var event=event||window.event;
	var target=event.target||event.srcElement;
	var clsName=target.className;
	if(clsName==='submit'){	//点击了确定按钮
		if(event.preventDefault){	//阻止事件默认行为
			event.preventDefault();
		}
		else{
			event.returnValue=false;
		}
		var id=target.getAttribute('id');
		if(id==='submit-profile'){	//点击了修改管理员资料的确定按钮
			setAdmin(target);
		}
		else if(id==='submit-page'){	//点击了添加页面的确定按钮
			addPage(target);
		}
		else if(id==='submit-theme'){	//点击了修改主题的确定按钮
			changeTheme(target);
		}
	}
	else if(clsName==='edit'||clsName==='update'||clsName==='quit'||clsName==='delete'){	//点击了页面编辑或修改或取消按钮
		editPage(target);
	}
}

/**
 *设置管理员资料
 *@param button 点击的按钮
 */
function setAdmin(button){
	//验证相关资料是否填写完整
	var username=$('new-username');
	var password=$('new-password');
	username.style.borderColor='#e1e1e1';
	password.style.borderColor='#e1e1e1';
	if(!username.value&&!password.value){	//三项信息均为空
		username.style.borderColor='#f90000';
		password.style.borderColor='#f90000';
		return ;
	}
	if(password.value){	//检查密码强度
		if(password.value.length<6){	//密码长度少于6个字符
			password.style.borderColor='#f90000';
			password.value='';
			password.setAttribute('placeholder','密码不要少于6个字符');
			return;
		}
	}
	var xhr=XHR();
	if(xhr){
		button.style.backgroundColor='#c0c0c0';
		var href='logic.php?act=updateAdmin';
		xhr.open('POST',href,true);
		xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				var result=json.result;
				if(success==1){	//管理员资料更新成功
					username.value=result.username;
					password.value='';
					//更新网页头部中的管理员用户名
					$('left-username').innerHTML=result.username;
					$('right-username').innerHTML=result.username;
					$('administrator').innerHTML='您好，'+result.username;
				}
				else if(success==0){	//更新失败，回显错误
					if(result==='empty information'){
						username.style.borderColor='#f90000';
						password.style.borderColor='#f90000';
					}
					else if(result==='weak password'){
						password.style.borderColor='#f90000';
						password.value='';
						password.setAttribute('placeholder','密码不要少于6个字符');
					}
				}
				button.style.backgroundColor='#007BB7';
				$('token').value=json.token;
			}
		}
		xhr.send('token='+$('token').value+'&username='+username.value+'&password='+password.value+'&id='+$('admin-id').value);
	}
}


/**
 *编辑页面
 *@param button 点击的按钮
 */
var lastEditPage;	//缓存上次编辑的页面名称单元格
var lastEditTemp;	//缓存上次编辑的页面模板单元格
var lastTarget;	//缓存上一次点击的按钮
var editing_page=$('editing-page');	//带插入的页面名称输入框
var editing_template=$('editing-template');	//带插入的页面模板输入框
var oldPage;	//缓存原先的页面名称
var oldTemplate;	//缓存原先的页面模板名称
function editPage(button){
	var clsName=button.className;
	var td=button.parentNode.parentNode.getElementsByTagName('td');
	var page=td[0];
	var template=td[1];
	if(clsName==='edit'){	//点击了编辑按钮
		page.style.textIndent='-1500px';
		template.style.textIndent='-1500px';
		var quit_btn=button.nextSibling;
		quit_btn.innerHTML='取消';
		quit_btn.className='quit';
		button.innerHTML='修改';
		button.className='update';
		editing_page.style.display='block';
		editing_page.style.borderColor='#e1e1e1';
		editing_template.style.display='block';
		editing_template.style.borderColor='#e1e1e1';
		if(lastTarget){	//不是第一次点击
			if(lastTarget===button){	//接连点击同一个编辑按钮
				return;
			}
			else{
				lastEditPage.style.textIndent='0px';
				lastEditTemp.style.textIndent='0px';
				lastTarget.innerHTML='编辑';
				lastTarget.className='edit';
				var del_btn=lastTarget.nextSibling;
				del_btn.innerHTML='删除';
				del_btn.className='delete';
			}
		}
		lastEditPage=page;
		lastEditTemp=template;
		lastTarget=button;
		oldPage=page.innerHTML;
		oldTemplate=template.innerHTML;
		editing_page.value=oldPage;
		editing_template.value=oldTemplate;
		page.appendChild(editing_page);
		template.appendChild(editing_template);
	}
	else if(clsName==='update'){	//点击修改按钮
		//检查输入域是否完整填写
		var newPage=editing_page.value;
		if(!newPage){
			editing_page.style.borderColor='#f90000';
			return;
		}
		var newTemplate=editing_template.value;
		if(newPage===oldPage&&newTemplate===oldTemplate){
			button.nextSibling.click();
			return;
		}
		if(!$(newTemplate)&&newTemplate!=='没有设置'){
			editing_template.style.borderColor='#f90000';
			editing_template.value='无效的页面模板';
			return;
		}
		var xhr=XHR();
		if(xhr){
			var href='logic.php?act=editPage&newPage='+newPage+'&newTemplate='+newTemplate+'&oldPage='+oldPage;
			xhr.open('GET',href,true);
			xhr.onreadystatechange=function(){
				if(xhr.readyState==4&&xhr.status==200){
					var json_str=xhr.responseText;
					var json=JSON.parse(json_str);
					var success=json.success;
					if(success==1){	//编辑页面成功
						page.innerHTML=newPage;
						template.innerHTML=newTemplate;
						lastTraget=null;
						button.nextSibling.click();
					}
				}
			}
			xhr.send(null);
		}
	}
	else if(clsName==='quit'){	//点击取消按钮
		button.innerHTML='删除';
		button.className='delete';
		var edit_btn=button.previousSibling;
		edit_btn.innerHTML='编辑';
		edit_btn.className='edit';
		page.style.textIndent='0px';
		template.style.textIndent='0px';
		editing_page.style.display='none';
		editing_template.style.display='none';
	}
	else if(clsName==='delete'){	//点击了删除按钮
		var confirmed=confirm('确定要删除此页面吗?页面对应的模板文件不会被删除!');
		if(confirmed){
			var xhr=XHR();
			if(xhr){
				var href='logic.php?act=delPage&page='+page.innerHTML;
				xhr.open('GET',href,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){	//删页面成功
							tbody=table.getElementsByTagName('tbody')[0];
							tbody.removeChild(button.parentNode.parentNode);
							controlMenubar();
							drawTable(table);
						}
					}
				}
				xhr.send(null);
			}
		}
	}
}

/**
 *删除页面
 *@param button 点击的按钮
 */
function deletePage(button){

}


/**
 *添加页面
 *@param button 点击的按钮
 */
function addPage(button){
	var page=$('new-page');
	if(!page.value){
		page.style.borderColor='#f90000';
		return;
	}
	var xhr=XHR();
	if(xhr){
		button.style.backgroundColor='#c0c0c0';
		var template=$('page-template');
		var href='logic.php?act=addPage&page='+page.value+'&template='+template.value;
		xhr.open('GET',href,true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				if(success==1){	//添加页面成功
					var tbody=table.getElementsByTagName('tbody')[0];
					var tr=document.createElement('tr');
					tr.innerHTML=$('tr-template').innerHTML;
					var td=tr.getElementsByTagName('td');
					td[0].innerHTML=page.value;
					td[1].innerHTML=template.value?template.value:'没有设置';
					tbody.appendChild(tr);
					controlMenubar();
					drawTable(table);
					page.style.borderColor='#e1e1e1';
					page.value='';
					button.style.backgroundColor='#007BB7';
				}
			}
		}
		xhr.send(null);
	}
}

/**
 *更改主题
 *@param button 点击的按钮
 */
function changeTheme(button){
	var theme=$('theme').value;
	var xhr=XHR();
	if(xhr){
		button.style.backgroundColor='#c0c0c0';
		var href='logic.php?act=changeTheme&theme='+theme;
		xhr.open('GET',href,true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				if(success==1){	//更改主题成功
					$('current-theme').innerHTML=theme;
					button.style.backgroundColor='#007BB7';
				}
			}
		}
		xhr.send(null);
	}
}