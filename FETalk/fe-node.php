<?php 
/*
*�ڵ�ҳ��
*/
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	$theme=THEME_NAME;
	
	/*
	*�鿴�ڵ�ҳ�棬��ʾ�ýڵ������еĻ�����Ϣ
	*/
	if(isset($_GET['n'])){
		if(is_numeric($_GET['n'])){
			require_once($theme.'/node.php');
		}
		else{
			exit('http error');
		}
	}
?>