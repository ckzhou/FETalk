<?php 
	/**
	 *用户信息页面
	*/
	
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	$theme=THEME_NAME;
	
	if(isset($_GET['u'])&&is_numeric($_GET['u'])){	//如果传递了正确的用户id
		if(isset($_GET['c'])){	//c即content,代表要查看的内容
			$content=$_GET['c'];
			if($content=='topics'){	//c=topics代表查看用户发表的话题
				require_once($theme.'/user-topics.php');
				exit;
			}
			else if($content=='replies'){	//c=replies代表查看用户发表的回复
				require_once($theme.'/user-replies.php');
				exit;
			}
			else if($content=='collections'){	//c=collect代表查看用户收藏的所有话题
				require_once($theme.'/user-collect.php');
				exit;
			}
		}
		else{	//没有设置c就显示用户的基本简档
			require_once($theme.'/user.php');
			exit;
		}
	}
	else{	//id传递不正确返回'http error'
		exit('http error');
	}
?>