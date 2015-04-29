<?php 
	/**
	 *呈现用户所有的未读提醒，包括@和私信
	 */
	 define('CALL_TOKEN','f2ecreek');
	 require_once('fe-include.php');
	 
	 if(!_checkLogin()){	//如果没有登录
		header('fe-login.php');
		exit;
	 }
	 
	 $theme=THEME_NAME;
	 
	 if(isset($_GET['c'])){	//c代表要查看的内容类型
		$content=$_GET['c'];
		if($content==='unread'){	//查看所有的未读内容
			require_once($theme.'/mention.php');
		}
		else if($content==='at'){	//查看@你的历史记录
			require_once($theme.'/allAt.php');
		}
		else if($content==='letter'){	//查看你的私信对话记录
			require_once($theme.'/letter.php');
		}
		else if($content==='dialogue'){	//查看某个对话的所有私信记录
			require_once($theme.'/dialogue.php');
		}
	 }
	 else{	//默认是查看所有未读的内容
		require_once($theme.'/mention.php');
	 }
?>