<?php 
/*
*登录页面
*/

define("CALL_TOKEN","f2ecreek");
require_once("fe-include.php");

if(_checkLogin()){	//如果已经处于登录状态
	header("Location:index.php");
	exit;
}
/**
 *验证POST过来的登录信息
 */
if(isset($_POST['login-btn'])){	//如果有登录信息传递过来
	$csrfToken=$_POST['csrfToken'];
	if(checkToken($csrfToken)){	//验证token
		array_walk($_POST,'input_walk');
		$email=$_POST['email'];
		$password=$_POST['pwd'];
		$error_array=array();
		if($email){	//如果填写了邮箱
			if(!preg_match('/^[a-zA-Z0-9]+?@[a-zA-Z0-9]+?\.(com)$|(cn)$/',$email)){	//如果填写了无效的邮箱地址
				$error_array['email']='无效的Email';
			}
			else if(_fetch("SELECT id FROM fe_user WHERE email='{$email}'","int")==0){ //如果邮箱没有注册
				$error_array['emial']='该Email还未注册';
			}
		}
		else{	//如果没有填写邮箱
			$error_array['email']='必须填写Email';
		}
		if($password){	//如果填写密码
			$length=strlen($password);
			if($length<6){	//如果密码填写少于6个字符
				$error_array['password']='密码长度过短)';
			}
		}
		else{	//如果没有填写密码
			$error_array['password']='必须填写密码';
		}
		if(count($error_array)==0){	//如果前面没有检验出错误
			if(checkDefriend($email)){	//如果已经被拉黑
				$error_array['rule']='该账号由于违反社区规则已被禁止登录';
			}
			else{	//没有被拉黑则继续验证密码
				$db_password=MD5($password);
				$query_person="SELECT id,username,password,uniqId FROM fe_user WHERE email='{$email}'";
				$person=_fetch($query_person,'array')[0];
				if($db_password!==$person['password']){	//如果密码错误
					$error_array['password']='密码错误';
				}
				else{	//密码正确，验证通过
					$_SESSION['user_index']=$person['id'];
					$_SESSION['username']=$person['username'];
					$_SESSION['uid']=$person['uniqId'];
					setcookie('username',$person['username'],time()+3600*24*30,'/','localhost',0,1);
					setcookie('uid',$person['uniqId'],time()+3600*24*30,'/','localhost',0,1);
					header('Location:index.php');
					exit;
				}
			}
		}
		$json=json_encode($error_array);
		$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
		$json_file=fopen($json_path,'wb');
		fwrite($json_file,$json);
		header('Location:fe-login.php');
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
	require_once($theme."/login.php");
?>
