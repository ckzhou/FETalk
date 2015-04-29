<?php 
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	if(!_checkLogin()){	//没有登录，跳转到登录页面
		header('Location:fe-login.php');
		exit;
	}
	else{
		/*
		*处理post过来的帖子信息
		*/
		if(isset($_POST['create-btn'])){
			$csrfToken=$_POST['csrfToken'];
			if(checkToken($csrfToken)){	//验证Token
				array_walk($_POST,'input_walk');
				$error_array=array();
				if(!$_POST['create-title']){
					$error_array['title']='请填写帖子标题';
				}
				else if(strlen($_POST['create-title'])<3){
					$error_array['title']='帖子标题长度过短(3-56个字符)';
				}
				else if(strlen($_POST['create-title'])>56){
					$error_array['title']='帖子标题长度过长(3-56个字符)';
				}
				if(!$_POST['create-text']){
					$error_array['text']='请填写帖子内容';
				}
				else if(strlen($_POST['create-text'])<15){
					$error_array['text']='帖子内容长度过短(少于15个字符)';
				}
				if(count($error_array)>0){
					$json=json_encode($error_array);
					$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
					$json_file=fopen($json_path,'wb');
					fwrite($json_file,$json);
					$node=$_COOKIE['nodeid'];
					header("Location:fe-create.php?n={$node}");
					exit;
				}
				$title=$_POST['create-title'];
				$parsedown=new parsedown();
				$text=$parsedown->text($_POST['create-text']);
				$time=time();
				$nodeId=$_COOKIE['nodeid'];
				$insertTopic="INSERT INTO fe_topic (uId,nodeId,cTime,title,text,final_reply_time) VALUES({$_SESSION['user_index']},{$nodeId},{$time},'{$title}','{$text}',{$time})";
				$isInserted=_insert($insertTopic);
				$topic_id=mysql_insert_id();
				save_dynamic($topic_id,'create_topic');
				cut_reputation();
				$mention=new mention($_SESSION['user_index'],$topic_id);
				$mention->save_mention($text);
				header('Location:'._path('home'));
			}
			else{
				echo 'http error';
			}
			exit;
		}
		
		$theme=THEME_NAME;
		
		/*
		*将get过来的节点id存储与cookie中，用于存储帖子信息的时候使用
		*/
		if(isset($_GET['n'])){
			$nodeid=$_GET['n'];
			if(is_numeric($nodeid)){
				setcookie('nodeid',$nodeid,0);
			}
			else{
				exit('http error');
			}
			require_once($theme."/create.php");
		}
	}
?>