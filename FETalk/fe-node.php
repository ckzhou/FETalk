<?php 
/*
*节点页面
*/
	define('CALL_TOKEN','f2ecreek');
	require_once('fe-include.php');
	
	$theme=THEME_NAME;
	
	/*
	*查看节点页面，显示该节点下所有的话题信息
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