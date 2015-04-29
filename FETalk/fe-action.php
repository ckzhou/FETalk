<?php
/*
*逻辑操作处理页面
*/
	define("CALL_TOKEN","f2ecreek");
	require_once("fe-include.php");
	
	if(!_checkLogin()){	//如果没有登录，跳转到登录页面
		header('Location:fe-login.php');
		exit;
	}
	if(isset($_GET['act'])){	//act代表操作类型
		$act=$_GET['act'];
		if($act==='out'){	//执行账号登出操作
			_logout();
		}
		else if($act==='collect'){	//执行话题收藏操作
			collect_topic();
		}
		else if($act==='vote'){	//执行话题投票操作
			vote_topic();
		}
		else if($act==='agree'){	//执行回复点赞操作
			agree_reply();
		}
		else if($act==='concern'){	//执行关注操作
			concern();
		}
		else if($act==='deliver'){	//执行发送私信操作
			send_letter();
		}
		else if($act==='unread'){	//执行获取未读提醒操作
			have_mention();
		}
		else if($act==='delDialogue'){	//执行删除对话记录的操作
			del_dialogue();
		}
		else if($act==='delLetter'){	//执行删除某一条私信的操作
			del_letter();
		}
		else if($act==='delAt'){	//执行删除at信息的操作
			del_at();
		}
	}
?>