<?php 
	define("CALL_TOKEN","f2ecreek");
	require_once("fe-include.php");
	
	if(_checkLogin()){	//如果已经登录
		header("Location:index.php");
		exit;
	}
	/**
	 *验证POST过来的登录信息
	 */
	if(isset($_POST['register-btn'])){
		$csrfToken=$_POST['csrfToken'];
		if(checkToken($csrfToken)){	//验证token
			array_walk($_POST,'input_walk');
			$error_array=array();
			$username=$_POST['username'];
			if($username){	//如果填写了用户名
				$length=strlen($username);
				if($length<3){	//如果用户名长度少于3个字符
					$error_array['username']='用户名长度过短(3-12个字符)';
				}
				else if($length>12){	//如果用户名多于12个字符
					$error_array['username']='用户名长度过长(3-12个字符)';
				}
				else if(!preg_match('/^[A-Za-z](([A-Za-z]|\d|_){2,11})$/',$username)){	//如果用户名不符合规则
					$error_array['username']='用户名不符合规则(由字母开头，只能包含字母，数字或者下划线)';
				}
				else if(_fetch("SELECT id FROM fe_user WHERE username='{$username}'","int")==1){	//如果用户名已被占用
					$error_array['username']='该用户名已经存在';
				}
			}
			else{	//如果用户名没有填写
				$error_array['username']='必须填写用户名';
			}
			$password=$_POST['pwd'];
			if($password){	//如果填写了密码
				$length=strlen($password);
				$confirmPwd=$_POST['confirmPwd'];
				if($length<6){	//密码长度少于6字符
					$error_array['passowrd']='密码长度过短';
				}
				else if($confirmPwd!=$password){	//两次密码输入不一致
					$error_array['password']='两次密码输入不一致';
				}
			}
			else{	//如果没有填写密码
				$error_array['password']='必须填写密码';
			}
			$email=$_POST['email'];
			if($email){	//如果填写了email
				if(!preg_match('/^[a-zA-Z0-9]+?@[a-zA-Z0-9]+?\.(com)$|(cn)$/',$email)){	//无效的email
					$error_array['email']='无效的Email';
				}
				else if(_fetch("SELECT id FROM fe_user WHERE email='{$email}'","int")==1){	//email已经注册
					$error_array['email']='该Email已经注册';
				}
			}
			else{	//如果没有填写email
				$error_array['email']='必须填写Email';
			}
			if(count($error_array)>0){	//前面检验出了错误
				$json=json_encode($error_array);
				$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
				$json_file=fopen($json_path,'wb');
				fwrite($json_file,$json);
				header('Location:fe-register.php');
				exit;
			}
			$db_password=MD5($password);
			$uniqId=uniqid(mt_rand(),true);
			$time=time();
			_insert("INSERT INTO fe_user (username,password,email,regTime,uniqId) VALUES('{$username}','{$db_password}','{$email}',{$time},'{$uniqId}')");
			$_SESSION['user_index']=mysql_insert_id();
			$_SESSION['username']=$username;
			$_SESSION['uid']=$uniqId;
			setcookie('username',$username,time()+3600*24*30,'/','localhost',0,1);
			setcookie('uid',$uniqId,time()+3600*24*30,'/','localhost',0,1);
			header('Location:index.php');
			exit;
		}
		else{
			exit('http error');
		}
	}
?>

<?php
	/**
	 *包含主题页面
	 */
	$theme=THEME_NAME;
	require_once(THEME_NAME."/register.php");
?>