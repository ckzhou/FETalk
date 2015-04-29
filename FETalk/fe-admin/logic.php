<?php 
	/**
	 *逻辑处理操作文件
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once('../fe-include.php');
	if(!_checkLogin('admin')){
		exit('404!not found!');
	}
	/**
	 *判断操作类型并执行相应的操作
	 */
	if(isset($_GET['act'])){	//act代表操作类型
		$act=$_GET['act'];
		if($act=='out'){	//执行账号登出操作
			_logout("admin");
		}
		else if($act=='addCategory'){	//添加新的节点分类
			add_category();
		}
		else if($act=='updateCategory'){	//修改分类名称
			update_category();
		}
		else if($act=='addNode'){	//添加新节点
			add_node();
		}
		else if($act=='updateNode'){	//修改节点信息
			update_node();
		}
		else if($act=='searchTopic'){	//搜索帖子
			search_topic();
		}
		else if($act=='deleteTopic'){	//删除帖子
			delete_topic();	
		}
		else if($act=='searchUser'){	//搜索用户
			search_user();
		}
		else if($act=='defriend'){	//拉黑用户
			defriend('yes');
		}
		else if($act=='relive'){	//解除拉黑
			defriend('not');
		}
		else if($act=='updateAdmin'){	//更新管理员的身份信息
			update_admin();
		}
		else if($act=='addPage'){	//添加页面
			add_page();
		}
		else if($act=='editPage'){	//编辑页面名称和页面模板
			edit_page();
		}
		else if($act=='delPage'){	//删除页面
			del_page();
		}
		else if($act=='changeTheme'){	//更改主题
			change_theme();
		}
	}
	
	
	
	
	
	
	if(isset($_POST['action'])){
		$csrf_token=$_POST['csrf_token'];
		if(checkToken($csrf_token)){
			$tempArr=array();
			$action=$_POST['action'];
			if($action==='addCategory'){
				$cateName=_clean($_POST['newCategoryName']);
				$cateDesc=_clean($_POST['description']);
				if(isset($cateName)){
					$newCateSql="INSERT INTO fe_category (cName,description) VALUES('{$cateName}','{$cateDesc}')";
					$isInserted=_insert($newCateSql);
					if($isInserted){
						$tempArr['status']='success';
						$tempArr['cateName']=$cateName;
						$tempArr['cateDesc']=$cateDesc;
					}
				}
				else if(!isset($cateName)){
					$tempArr['status']='error';
					$tempArr['message']='noCategory';
				}
				$outcomeJson=json_encode($tempArr);
				echo $outcomeJson;
				exit;
			}
			if($action==='updateCategory'){
				$originalName=_clean($_POST['originalName']);
				$updatedName=_clean($_POST['updatedName']);
				$updatedDesc=_clean($_POST['updatedDesc']);
				if(isset($updatedName)){
					$updateSql="UPDATE fe_category SET cName='{$updatedName}',description='{$updatedDesc}' WHERE cName='{$originalName}'";
					$isUpdated=_update($updateSql);
					$tempArr['status']='success';
				}
				else if(!isset($updatedName)){
					$tempArr['status']='error';
					$tempArr['message']='noCategory';
				}
				$outcomeJson=json_encode($tempArr);
				echo $outcomeJson;
				exit;
			}
			if($action==='addNode'){
				$nodeName=_clean($_POST['nodeName']);
				$belongCate=_clean($_POST['belongCate']);
				if(isset($nodeName)&&isset($belongCate)){
					$addNodeSql="INSERT INTO fe_node (nName,cId) VALUES('{$nodeName}',{$belongCate})";
					$isInserted=_insert($addNodeSql);
					if($isInserted){
						$tempArr['status']='success';
						$tempArr['nodeName']=$nodeName;
						$belongCateName=_fetch("SELECT cName FROM fe_category WHERE id={$belongCate}",'array')[0]['cName'];
						$tempArr['belongCate']=$belongCateName;
					}
					else{
						$tempArr['status']='error';
						$tempArr['message']='not complete information';
					}
					$outcomeJson=json_encode($tempArr);
					echo $outcomeJson;
					exit;
				}
			}
			if($action==='updateNode'){
				$originalNode=_clean($_POST['originalNode']);
				$updatedNode=_clean($_POST['updatedNode']);
				$updatedCate=_clean($_POST['updatedCate']);
				if($updatedNode&&$updatedCate){
					$getCateId="SELECT id FROM fe_category WHERE cName='{$updatedCate}'";
					$cateId=_fetch($getCateId,'array')[0]['id'];
					$updateNode="UPDATE fe_node SET cId={$cateId},nName='{$updatedNode}' WHERE nName='{$originalNode}'";
					$isUpdated=_update($updateNode);
					$tempArr['status']='success';
				}
				else{
					$tempArr['status']='error';
					$tempArr['message']='not completed information';
				}
				$outcomeJson=json_encode($tempArr);
				echo $outcomeJson;
				exit;
			}
		}
	}
?>