/**
 *美化分类表格
 */
var table=$('categoryTable');
drawTable(table);

/**
 *给添加分类按钮绑定点击事件
 */
var add_btn=$('submit');
addEvent(add_btn,'click',addCategory);

/**
 *事件处理程序，检验表单填写是否正确，如果正确，发送信息至服务端
 */
function addCategory(event){
	var event=event||window.event;
	if(event.preventDefault){	//阻止事件默认行为
		event.preventDefault();
	}
	else{
		event.returnValue=false;
	}
	//验证表单填写
	var category=$('newCategory');
	if(!category.value){	//如果没有填写输入域,设置其边框颜色为红色以提醒
		category.style.borderColor='#f90000';
		return;
	}
	//填写无误，ajax发送信息
	var xhr=XHR();
	if(xhr){	//如果XMLHttpRequest对象创建成功
		add_btn.style.backgroundColor='#c0c0c0';	//数据处理期间按钮处于灰色背景
		var token=$('token').value;
		var href='logic.php?act=addCategory&category='+category.value+'&token='+token;
		xhr.open('GET',href,true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				if(success==1){	//添加新分类成功
					//表格中插入新行
					var newCategory=document.createElement('tr');
					newCategory.innerHTML=$('tr-template').innerHTML;
					var td=newCategory.getElementsByTagName('td');
					td[0].innerHTML=category.value;
					td[1].innerHTML=0;
					var tbody=table.getElementsByTagName('tbody')[0];	//浏览器在解析html时会自动加上tbody标签
					var first_tr=tbody.getElementsByTagName('tr')[1];
					tbody.insertBefore(newCategory,first_tr);
					//表单恢复原状
					add_btn.style.backgroundColor='#007BB7';
					category.value='';
					category.style.borderColor='#e1e1e1';
				}
			}
		}
		xhr.send(null);
	}
}

/**
 *为table绑定点击事件
 */
addEvent(table,'click',editCategory);

/**
 *事件处理程序，编辑分类名称
 */
var lastEdit;	//用来缓存上一次被编辑的td节点
var lastTarget;	//用来缓存上一次被点击的编辑按钮
var edit_box=$('editing');	//待插入的输入框
var quit_btn=$('quit');	//待插入的取消按钮
var oldCategory;	//缓存原先的分类名称
function editCategory(event){
	var event=event||window.event;
	var target=event.target||event.srcElement;
	var clsName=target.className;
	if(clsName=='edit'){	//如果点击的是编辑按钮
		var category=target.parentNode.parentNode.getElementsByTagName('td')[0];
		category.style.textIndent='-1500px';
		target.className='update';
		target.innerHTML='修改';
		target.style.margin='0 5px 0 30px';
		edit_box.style.display='block';
		quit_btn.style.display='inline';
		if(lastTarget){	//恢复上一次被点击的编辑按钮
			if(lastTarget===target){	//接连点击相同的编辑按钮
				return;
			}
			else{
				lastTarget.innerHTML='编辑';
				lastTarget.className='edit';
				lastTarget.style.margin='0px';
				lastEdit.style.textIndent='0px';
			}
		}
		lastTarget=target;
		oldCategory=category.innerHTML;
		lastEdit=category;
		edit_box.value=oldCategory;
		category.appendChild(edit_box);
		var edition=target.parentNode;
		edition.appendChild(quit_btn);
	}
	else if(clsName=='update'){	//点击修改按钮
		var newCategory=edit_box.value;
		if(!newCategory){	//没有填写新的分类名称
			edit_box.style.borderColor='#f90000';
			return;
		}
		else if(oldCategory===newCategory){	//如果没有对分类名称进行修改
			quit_btn.click();
			return;
		}
		else{	
			var xhr=XHR();
			if(xhr){	//XMLHttpRequest对象创建成功
				var href='logic.php?act=updateCategory&newCategory='+newCategory+'&oldCategory='+oldCategory;
				xhr.open('GET',href,true);
				xhr.onreadystatechange=function(){
					if(xhr.readyState==4&&xhr.status==200){
						var json_str=xhr.responseText;
						var json=JSON.parse(json_str);
						var success=json.success;
						if(success==1){	//修改分类名称成功
							quit_btn.click();	//手动触发取消按钮的点击事件
							lastEdit.innerHTML=newCategory;	//更改单元格中的分类名称
							lastTarget=null;
						}
					}
				}
				xhr.send(null);
			}
		}
	}
	else if(clsName=='quit'){	//点击取消按钮
		lastEdit.style.textIndent='0px';
		lastTarget.innerHTML='编辑';
		lastTarget.className='edit';
		lastTarget.style.margin='0px';
		edit_box.style.display='none';
		quit_btn.style.display='none';
	}
}