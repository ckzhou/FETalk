<?php 
	/**
	 *密码找回页面
	 */
	define("CALL_TOKEN","f2ecreek");
	require_once("fe-include.php");
	
	/**
	 *处理POST过来的用户名和邮箱信息，发送临时的新密码
	 */
	if(isset($_POST['retrieve-btn'])){
		$token=$_POST['csrfToken'];
		checkToken($token);
		array_walk($_POST,'input_walk');
		$error_array=array();
		$username=$_POST['username'];
		if($username){	//填写了用户名
			if(!preg_match('/^[A-Za-z](([A-Za-z]|\d|_){2,11})$/',$username)){	//用户名不符合规则
				$error_array['username']='用户名不符合规则(由字母开头，只能包含字母，数字或者下划线)';
			}
		}
		else{	//没有填写用户名
			$error_array['username']='必须填写用户名';
		}
		$email=$_POST['email'];
		if($email){	//填写了邮箱
			if(!preg_match('/^[a-zA-Z0-9]+?@[a-zA-Z0-9]+?\.(com)$|(cn)$/',$email)){	//无效的Email
				$error_array['email']='无效的Email';
			}
		}
		else{	//没有填写邮箱
			$error_array['email']='必须填写邮箱';
		}
		if(count($error_array)==0){	//如果前面没有检测出错误
			$query_user="SELECT id FROM fe_user WHERE email='{$email}' AND username='{$username}'";
			if(_fetch($query_user,"int")==0){	//用户名与邮箱如果不匹配
				$error_array['user']='所填用户名和邮箱有误';
			}
			else{	//用户名与邮箱匹配，向其邮箱发送临时密码
				$tmp_password=uniqid(mt_rand());
				$db_password=MD5($tmp_password);
				_update("UPDATE fe_user SET password='{$db_password}' WHERE email='{$email}'");
				$subject="FETalk找回密码";
				$message="Welcome to FETalkr\n您成功重置了在FETalk的会员密码\r\n\r\n#您的注册邮箱:{$email}\r\n#您的随机密码:{$tmp_password}\r\n#请您尽快登录FETalk社区修改密码";
				$mailer=new sendmail($email,$subject,$message);
				$mailer->send();
				header("Location:fe-login.php");
				exit;
			}
		}
		$json=json_encode($error_array);
		$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
		$json_file=fopen($json_path,'wb');
		fwrite($json_file,$json);
		header("Location:fe-forgot.php");
		exit;
	}
	 
	$theme=THEME_NAME;
	
	require_once($theme."/forgot.php");
?>
