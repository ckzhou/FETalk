/**
 *美化分类表格
 */
var table=$('nodeTable');
drawTable(table);


/**
 *给添加按钮绑定点击事件
 */
var add_btn=$('submit');
addEvent(add_btn,'click',addNode);

/**
 *事件处理程序，添加新节点
 */
function addNode(event){
	var event=event||window.event;
	if(event.preventDefault){	//阻止事件默认行为
		event.preventDefault();
	}
	else{
		event.returnValue=false;
	}
	//验证表单是否填写完整
	var node=$('newNode');
	if(!node.value){	//没有填写新的节点名称
		node.style.borderColor='#f90000';
		return;
	}
	var xhr=XHR();
	if(xhr){	//XMLHttpRequest对象创建成功
		add_btn.style.backgroundColor='#c0c0c0';
		var category=$('belongCate');
		var token=$('token').value;
		var href='logic.php?act=addNode&newNode='+node.value+'&category='+category.value+'&token='+token;
		xhr.open('GET',href,true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4&&xhr.status==200){
				var json_str=xhr.responseText;
				var json=JSON.parse(json_str);
				var success=json.success;
				if(success==1){	//添加节点成功
					//表格中插入新行
					var newNode=document.createElement('tr');
					newNode.innerHTML=$('template').innerHTML;
					var td=newNode.getElementsByTagName('td');
					td[0].innerHTML=node.value;
					var option=category.getElementsByTagName('option');
					var index=parseInt(category.value)-1;
					td[1].innerHTML=option[index].innerHTML;
					td[2].innerHTML=0;
					var tbody=table.getElementsByTagName('tbody')[0];
					var first_tr=tbody.getElementsByTagName('tr')[1];
					tbody.insertBefore(newNode,first_tr);
					//添加表单恢复原状
					node.style.borderColor='#e1e1e1';
					node.value='';
					add_btn.style.backgroundColor='#007BB7';
				}
				drawTable(table);
				controlMenubar();
			}
		}
		xhr.send(null);
	}
}

/**
 *为table绑定点击事件
 */
addEvent(table,'click',editNode);

/**
 *事件处理程序，实现对已创建节点的编辑
 */
var lastEditNode;	//缓存上次编辑的节点单元格
var lastEditCate;	//缓存上次编辑的节点分类单元格
var lastTarget;	//缓存上次点击的编辑按钮
var oldNode;	//缓存原先的节点名称
var oldCategory;	//缓存原先的分类名称
var editing_node=$('editing-node');	//带插入的节点编辑框
var editing_category=$('editing-category');	//待插入的分类编辑框
var quit_btn=$('quit');	//带插入的取消按钮
function editNode(event){
	var event=event||window.event;
	var target=event.target||event.srcElement;
	var clsName=target.className;
	if(clsName==='edit'){	//点击了编辑按钮
		var td=target.parentNode.parentNode.getElementsByTagName('td');
		var node=td[0];
		var category=td[1];
		var edition=td[3];
		node.style.textIndent='-1500px';
		category.style.textIndent='-1500px';
		target.style.margin='0 5px 0 30px';
		target.innerHTML='修改';
		target.className='update';
		editing_node.style.display='block';
		editing_node.style.borderColor='#e1e1e1';
		editing_category.style.display='block';
		editing_category.style.borderColor='#e1e1e1';
		quit_btn.style.display='inline';
		if(lastTarget){
			if(lastTarget===target){
				return;
			}
			else{
				lastTarget.style.margin='0px';
				lastTarget.innerHTML='编辑';
				lastTarget.className='edit';
				lastEditNode.style.textIndent='0px';
				lastEditCate.style.textIndent='0px';
			}
		}
		lastEditNode=node;
		lastEditCate=category;
		lastTarget=target;
		oldNode=node.innerHTML;
		oldCategory=category.innerHTML;
		editing_node.value=oldNode;
		node.appendChild(editing_node);
		editing_category.value=oldCategory;
		category.appendChild(editing_category);
		edition.appendChild(quit_btn);
	}
	else if(clsName==='update'){	//点击了修改按钮
		//验证输入框是否填写完整
		var newNode=editing_node.value;
		if(!newNode){
			editing_node.style.borderColor='#f90000';
			return;
		}
		var newCategory=editing_category.value;
		if(!newCategory){
			editing_category.style.borderColor='#f90000';
			return;
		}
		if(newNode===oldNode&&newCategory===oldCategory){
			quit_btn.click();
			return;
		}
		var option=$(newCategory);
		if(option){
			var category_id=option.value;
		}
		else{
			editing_category.style.borderColor='#f90000';
			editing_category.value='无效的分类';
			return;
		}
		var xhr=XHR();
		if(xhr){
			var href='logic.php?act=updateNode&newNode='+newNode+'&oldNode='+oldNode+'&category='+category_id;
			xhr.open('GET',href,true);
			xhr.onreadystatechange=function(){
				if(xhr.readyState==4&&xhr.status==200){
					var json_str=xhr.responseText;
					var json=JSON.parse(json_str);
					var success=json.success;
					if(success==1){	//修改节点成功
						lastEditNode.innerHTML=newNode;
						lastEditCate.innerHTML=newCategory;
						quit_btn.click();
						lastTarget=null;
					}
				}
			}
			xhr.send(null);
		}
	}
	else if(clsName==='quit'){	//点击了取消按钮
		lastEditNode.style.textIndent='0px';
		lastEditCate.style.textIndent='0px';
		lastTarget.style.margin='0px';
		lastTarget.innerHTML='编辑';
		lastTarget.className='edit';
		editing_node.style.display='none';
		editing_category.style.display='none';
		editing_category.value=oldCategory;
		quit_btn.style.display='none';
	}
}


