<?php 
	/**
	 *�����û����е�δ�����ѣ�����@��˽��
	 */
	 define('CALL_TOKEN','f2ecreek');
	 require_once('fe-include.php');
	 
	 if(!_checkLogin()){	//���û�е�¼
		header('fe-login.php');
		exit;
	 }
	 
	 $theme=THEME_NAME;
	 
	 if(isset($_GET['c'])){	//c����Ҫ�鿴����������
		$content=$_GET['c'];
		if($content==='unread'){	//�鿴���е�δ������
			require_once($theme.'/mention.php');
		}
		else if($content==='at'){	//�鿴@�����ʷ��¼
			require_once($theme.'/allAt.php');
		}
		else if($content==='letter'){	//�鿴���˽�ŶԻ���¼
			require_once($theme.'/letter.php');
		}
		else if($content==='dialogue'){	//�鿴ĳ���Ի�������˽�ż�¼
			require_once($theme.'/dialogue.php');
		}
	 }
	 else{	//Ĭ���ǲ鿴����δ��������
		require_once($theme.'/mention.php');
	 }
?>