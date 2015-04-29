<?php 
	/**
	 *用户资料设置页面
	 *@param $_GET['set_type']的值分别代表简档，头像，密码设置
	 *@param $error_array 保存表单的填写错误，最终被转化为json文件保存于服务器
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	if(!_checkLogin()){	//如果没有登录，跳转到登录页面
		header('Location:fe-login.php');
		exit;
	}
	
	/**
	 *处理post过来的数据,更新用户资料
	 */
	if(isset($_POST)&&count($_POST)>0){
		
		$csrfToken=$_POST['csrfToken'];
		checkToken($csrfToken);	//验证token
		
		/**
		 *更新用户简档
		 */
		if(isset($_POST['update-profile'])){
			array_walk($_POST,'input_walk');
			$error_array=array();
			if($_POST['signature']){
				if(strlen($sinature)>50){
					$error_array['signature']='个性签名不要超过50个字符';
				}
			}
			if($_POST['city']){
				if(strlen($city)>20){
					$error_array['city']='城市名称不要超过20个字符';
				}
			}
			if($_POST['company']){
				if(strlen($company)>50){
					$error_array['company']='公司名称不要超过50个字符';
				}
			}
			if($_POST['introduction']){
				if(strlen($__POST['introduction'])>200){
					$error_array['introduction']='个人简介不要超过200个字符';
				}
			}
			$url_pattern='/^(http:\/\/|https:\/\/){1}([a-zA-Z0-9]|\.)?[a-zA-Z0-9]+\.[a-zA-Z]{2,4}[a-zA-Z\/#\?\-_=&\.]*/';
			if($_POST['github']){
				if(preg_match_all($url_pattern,$_POST['github'])!=1){
					$error_array['github']='Github地址格式错误';
				}
			}
			if($_POST['weibo']){
				if(preg_match_all($url_pattern,$_POST['weibo'])!=1){
					$error_array['weibo']='微博个性域名格式错误';
				}
			}
			if($_POST['douban']){
				if(preg_match_all($url_pattern,$_POST['douban'])!=1){
					$error_array['douban']='豆瓣个性域名格式错误';
				}
			}
			if($_POST['blog']){
				if(preg_match_all($url_pattern,$_POST['blog'])!=1){
					$error_array['blog']='博客地址格式错误';
				}
			}
			if(count($error_array)>0){
				$json=json_encode($error_array);
				$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
				$json_file=fopen($json_path,'wb');
				fwrite($json_file,$json);
				header('Location:fe-setting.php?set_type=basic');
				exit;
			}
			$signature=$_POST['signature'];
			$city=$_POST['city'];
			$company=$_POST['company'];
			$introduction=$_POST['introduction'];
			$blog=$_POST['blog']?('<a href="'.$_POST['blog'].'" class="blog">'.$_POST['blog'].'</a>'):$_POST['blog'];
			$github=$_POST['github']?('<a href="'.$_POST['github'].'" class="github">'.$_POST['github'].'</a>'):$_POST['github'];
			$weibo=$_POST['weibo']?('<a href="'.$_POST['weibo'].'" class="weibo">'.$_POST['weibo'].'</a>'):$_POST['weibo'];
			$douban=$_POST['douban']?('<a href="'.$_POST['douban'].'" class="douban">'.$_POST['douban'].'</a>'):$_POST['douban'];
			$query="UPDATE fe_user SET ";
			$exception_keys=array('csrfToken','update-profile','username','email');
			foreach($_POST as $key=>$value){
				if(!in_array($key,$exception_keys)){
					$query.="{$key}='${$key}',";
				}
			}
			$query=substr($query,0,-1)." WHERE id={$_SESSION['user_index']}";
			_update($query);
			header("Location:fe-setting.php?set_type=basic");
			exit;
		}
		
		/**
		 *更新用户头像
		 */
		if(isset($_POST['update-face'])){
			$file_name=validate_image();
			if($file_name!==null){
				$have_face=_fetch("SELECT id FROM fe_face WHERE uId={$_SESSION['user_index']}","int");
				if($have_face==1){
					$old_face=_fetch("SELECT fName FROM fe_face WHERE uId={$_SESSION['user_index']}","array")[0]['fName'];
					$path=dirname(__FILE__).'/fe-content/fe-face/';
					unlink($path.'max_'.$old_face);
					unlink($path.'big_'.$old_face);
					unlink($path.$old_face);
					$query="UPDATE fe_face SET fName='{$file_name}' WHERE uId={$_SESSION['user_index']}";
					_update($query);
				}
				else{
					$query="INSERT INTO fe_face (uId,fName) VALUES ({$_SESSION['user_index']},'{$file_name}')";
					_insert($query);
				}
				header('Location:fe-setting.php?set_type=face');
				exit;
			}
			else{
				exit('http error');
			}
		}
		
		/**
		 *更新用户的密码
		 */
		 if(isset($_POST['update-pwd'])){
			array_walk($_POST,'input_walk');
			$error_array=array();
			$current_pwd=$_POST['current-password'];
			$new_pwd=$_POST['new-password'];
			$confirm_pwd=$_POST['confirm-password'];
			if(!$current_pwd){
				$error_array['currentpwd']='必须填写当前密码';
			}
			else if(!$new_pwd){
				$error_array['newpwd']='必须填写新密码';
			}
			else if(strlen($new_pwd)<6){
				$error_array['length']='密码长度过短';
			}
			else if($new_pwd!=$confirm_pwd){
				$error_array['same']='两次密码输入不一致';
			}
			else{
				$db_password=MD5($current_pwd);
				$is_owner=_fetch("SELECT id FROM fe_user WHERE id={$_SESSION['user_index']} AND password='{$db_password}'","int");
				if($is_owner===1){
					$db_password=MD5($new_pwd);
					_update("UPDATE fe_user SET password='{$db_password}' WHERE id={$_SESSION['user_index']}");
				}
				else{
					$error_array['correct']='当前密码输入有误';
				}
			}
			if(count($error_array)>0){
				$json=json_encode($error_array);
				$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
				$json_file=fopen($json_path,'wb');
				fwrite($json_file,$json);
			}
			header("location:fe-setting.php?set_type=pwd");
			exit;
		 }
	}
	
	$theme=THEME_NAME;
	
	/**
	 *根据get过来的$set_type显示相应的页面
	 */
	if(isset($_GET['set_type'])){
		$set_type=$_GET['set_type'];
		if($set_type==='basic'){
			require_once($theme.'/setProfile.php');
		}
		else if($set_type==='face'){
			require_once($theme.'/setFace.php');
		}
		else if($set_type==='pwd'){
			require_once($theme.'/setPwd.php');
		}
		else{
			exit('http error');
		}
	}
	else{
		$set_type='basic';
		require_once($theme.'/setProfile.php');
	}
?>