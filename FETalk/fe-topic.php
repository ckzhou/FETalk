<?php 
/*
*话题页面
*/
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	$theme=THEME_NAME;
	
	/*
	*查看话题页面，显示有关这一条话题的所有信息
	*/
	if(isset($_GET['t'])){
		if(is_numeric($_GET['t'])){
			click_times_add($_GET['t']);
			$_SESSION['topic_id']=$_GET['t'];
			require_once($theme.'/topic.php');
			exit;
		}
	}
	
	/*
	*处理提交话题回复的逻辑操作
	*/
	if(isset($_POST['submitReply'])){
		$csrfToken=$_POST['csrfToken'];
		if(checkToken($csrfToken)){	//如果token正确
			array_walk($_POST,'input_walk');
			$error_array=array();
			if(!$_POST['reply-text']){
				$error_array['reply']='请填写回复内容';
				$json=json_encode($error_array);
				$json_path=dirname(__FILEE__).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
				$json_file=fopen($json_path,'wb');
				fwrite($json_file,$json);
				header("Location:fe-topic.php?t={$_SESSION['topic_id']}");
				exit;
			}
			$reply_text=$_POST['reply-text'];
			$parsedown=new parsedown();
			$reply_text=$parsedown->text($reply_text);
			$time=time();
			_update("UPDATE fe_topic SET final_reply_time={$time} WHERE id={$_SESSION['topic_id']}");//更新该条话题的final_reply_time
			$insert_reply="INSERT INTO fe_reply (uId,tId,text,cTime) VALUES ({$_SESSION['user_index']},{$_SESSION['topic_id']},'{$reply_text}',{$time})";
			$is_inserted=_insert($insert_reply);
			$reply_id=mysql_insert_id();
			raise_reputation($_SESSION['topic_id'],'high');//有人回帖，增加发帖者15个荣誉值
			save_dynamic($_SESSION['topic_id'],'create_reply');
			$mention=new mention($_SESSION['user_index'],$_SESSION['topic_id'],$reply_id);
			$mention->save_mention($reply_text);
			header("Location:fe-topic.php?t={$_SESSION['topic_id']}");
			exit;
		}
	}
?>