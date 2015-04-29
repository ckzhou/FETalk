<?php 
	/**
	 *编辑已经发表的帖子或者回复
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	if(!_checkLogin()){	//如果没有登录
		header('Location:fe-login.php');
		exit;
	}
	
	/**
	 *修改已经创建的话题
	 */
	if(isset($_POST['edit-topic'])){
		$csrfToken=$_POST['csrfToken'];
		if(checkToken($csrfToken)){	//验证Token
			array_walk($_POST,'input_walk');
			$error_array=array();
			if(!$_POST['new-title']){
				$error_array['title']='请填写帖子标题';
			}
			else if(strlen($_POST['new-title'])<3){
				$error_array['title']='帖子标题长度过短(3-56个字符)';
			}
			else if(strlen($_POST['new-title'])>56){
				$error_array['title']='帖子标题长度过长(3-56个字符)';
			}
			if(!$_POST['new-text']){
				$error_array['text']='请填写帖子内容';
			}
			else if(strlen($_POST['new-text'])<15){
				$error_array['text']='帖子内容长度过短(少于15个字符)';
			}
			if(count($error_array)>0){
				$json=json_encode($error_array);
				$json_path=dirname(__FILE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
				$json_file=fopen($json_path,'wb');
				fwrite($json_file,$json);
				header("Location:fe-edit.php?c=topic&t={$_POST['topic-id']}");
				exit;
			}
			$title=$_POST['new-title'];
			$parsedown=new parsedown();
			$text=$parsedown->text($_POST['new-text']);
			$topic_id=$_POST['topic-id'];
			$time=time();
			$modify_topic="UPDATE fe_topic SET title='{$title}',text='{$text}',final_reply_time={$time} WHERE id={$topic_id}";
			_update($modify_topic);
			$mention=new mention($_SESSION['user_index'],$topic_id);
			$mention->save_mention($text);
			header('Location:'._path('home'));
		}
		else{
			echo 'http error';
		}
		exit;
	}
	
	/**
	 *修改已经发表的回复
	 */
	if(isset($_POST['edit-reply'])){
		$csrfToken=$_POST['csrfToken'];
		if(checkToken($csrfToken)){	//验证Token
			array_walk($_POST,'input_walk');
			$error_array=array();
			if(!$_POST['new-text']){
				$error_array['reply']='请填写回复内容';
				$json=json_encode($error_array);
				$json_path=dirname(__FILEE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
				$json_file=fopen($json_path,'wb');
				fwrite($json_file,$json);
				header("Location:fe-edit.php?c=reply&r={$_POST['reply-id']}");
				exit;
			}
			$new_text=$_POST['new-text'];
			$parsedown=new parsedown();
			$new_text=$parsedown->text($new_text);
			$reply_id=$_POST['reply-id'];
			$topic_id=$_POST['topic-id'];
			$time=time();
			$update_reply="UPDATE fe_reply SET text='{$new_text}' WHERE id={$reply_id}";
			_update($update_reply);
			$mention=new mention($_SESSION['user_index'],$topic_id,$reply_id);
			$mention->save_mention($new_text);
			header("Location:fe-topic.php?t={$topic_id}");
			exit;
		}
		else{
			exit('http error');
		}
	}
	
	$theme=THEME_NAME;
	
	if(isset($_GET['c'])){
		$content=$_GET['c'];
		if($content==='topic'){	//编辑已发表的话题
			require_once($theme.'/edit-topic.php');
		}
		else if($content==='reply'){	//编辑已发表的回复
			require_once($theme.'/edit-reply.php');
		}
		else{
			exit('http error');
		}
	}
	
?>