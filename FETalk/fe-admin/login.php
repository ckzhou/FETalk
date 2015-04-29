<?php 
	/**
	 *网站后台登录页面
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once('../fe-include.php');
	
	if(_checkLogin('admin')){	//已经以管理员的身份登录
		header("Location:admin.php");
		exit;
	}
	
	/**
	 *处理客户端POST过来的登录信息
	 */
	if(isset($_POST['login-btn'])){	//如果有登录信息传递过来
		$token=$_POST['csrfToken'];
		checkToken($token);	//验证token
		array_walk($_POST,'input_walk');
		$error_array=array();
		$username=$_POST['username'];
		$password=$_POST['password'];
		if(!$username){	//没有填写用户名
			$error_array['username']='用户名一栏为空';
		}
		else if(!$password){	//没有填写密码
			$error_array['password']='密码一栏为空';
		}
		else if(_fetch("SELECT id FROM fe_admin WHERE username='{$username}'","int")==0){	//用户名不存在
			$error_array['username']='无效的用户名';
		}
		else{	//验证密码的正确性
			$db_password=MD5($password);
			if(_fetch("SELECT id FROM fe_admin WHERE username='{$username}' AND password='{$db_password}'","int")==0){	//密码错误
				$error_array['password']='密码错误';
			}
		}
		if(count($error_array)>0){	//前面检测出了错误
			$json=json_encode($error_array);
			$json_path=dirname(dirname(__FILE__)).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
			$json_file=fopen($json_path,'wb');
			fwrite($json_file,$json);
			header("Location:login.php");
			exit;
		}
		else{	//通过登录验证
			$info=_fetch("SELECT id,uniqId FROM fe_admin WHERE username='{$username}'","array")[0];
			$_SESSION['adminId']=$info['id'];
			$_SESSION['admin']=$username;
			if(isset($_POST['setcookie'])&&$_POST['setcookie']=='yes'){	//登录的时候勾选了记住我的复选框
				setcookie('adminId',$info['uniqId'],time()+3600*24*30,'/','localhost',0,1);
				setcookie('admin',$username,time()+3600*24*30,'/','localhost',0,1);
			}
			header("Location:admin.php");
			exit;
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin>>Login</title>
<meta http-equiv="Content-type" content="text/html;charset=utf-8"/>
<script type="text/javascript">
</script>
<link rel="stylesheet" type="text/css" href="css/login.css"/>
<link rel="shortcut icon" href="../e.ico"/>
</style>
</head>
<body>
	<div class="wrapper" id="wrapper">
		<div class="logo">
			<img src="css/image/fe.jpg" alt="logo-pic" class="logo-pic"/>
		</div>
		<?php echo get_error_file();?>
		<div class="login" id="login">
			<form method="POST" action="#" class="login-form" id="login-form">
				<input type="hidden" name="csrfToken" value="<?php echo _create_token();?>" class="csrf_token" id="csrf_token"/>
				<div class="form-control">
					<label for="username">用户名</label>
					<input type="text" name="username" class="username input" id="username"/>
				</div>
				<div class="form-control">
					<label for="password">密码</label>
					<input type="password" name="password" class="password input" id="password"/>
				</div>
				<div class="form-control bottom">
					<div class="log-identification">
						<input type="checkbox" name="setcookie" value="yes" class="identification" id="setcookie"/>
						<label for="setcookie">记住我的登录信息</label>
					</div>
					<div class="btn">
						<input  type="submit" class="login-btn" id="login-btn" name="login-btn" value="登录"/>
					</div>
				</div>
			</form>
			<a href="" class="forgot-pwd-link">忘记密码?</a>
		</div>
	</div>
	<script type="text/javascript">
		/**
		 *使登录表单左右摇晃的对象
		 */
		function shaking(){
			this.shaker=document.getElementById('login-form');	//摇晃对象为登录表单
		}
		shaking.prototype.generator=function(){	//生成左右摇晃的偏移量
			this.offsets=new Array();
			this.times=10;	//登录表单左右摇晃的总次数
			for(var i=0;i<this.times;i++){	
				var offset=Math.ceil((Math.random()+3)*3);	//9=<偏移量<=12
				if(i%2==0){	//向左的偏移量
					this.offsets.push(offset);
				}
				else{	//向右的偏移量
					this.offsets.push(-offset);
				}
			}
			this.scale=0;	//记录目前表单已经摇晃的次数
		}
		shaking.prototype.counter=function(){	//摇晃次数计数器函数
			if(this.scale<this.times){
				var offset=this.offsets[this.scale];
				var position=parseInt(getComputedStyle(this.shaker)['left']);
				var distance=Math.abs(position)+Math.abs(offset);	//表单每次摇晃需要移动的水平距离
				if(offset>0){	//向右偏移
					this.mover(1,distance,this);
				}
				else{	//向左偏移
					this.mover(-1,distance,this);
				}
				this.scale+=1;
				var _this=this;	//缓存当前对象
				setTimeout(function(){_this.counter()},50);
			}
			else{	//表单位置复位
				this.shaker.style.left='0px';
			}
		}
		shaking.prototype.mover=function(sign,distance){	//摇晃移动函数
			var speed=sign*Math.ceil(distance*0.6);	//表单移动的速度
			this.shaker.style.left=parseInt(getComputedStyle(this.shaker)['left'])+speed+'px';
			distance-=Math.abs(speed);
			var _this=this;	//缓存当前对象
			if(distance>0){	
				setTimeout(function(){_this.mover()},10);
			}
		}
		
		/**
		 *如果HTML中存在错误信息，表单摇晃
		 */
		
		var ul=document.getElementById('form-error');
		if(ul){
			setTimeout(function(){
				var shaker=new shaking();
				shaker.generator();
				shaker.counter();
			},500);
		}
	</script>
</body>
</html>

 
