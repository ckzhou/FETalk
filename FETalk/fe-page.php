<?php 
	define("CALL_TOKEN","f2ecreek");
	require_once("fe-include.php");
	if(isset($_GET['pId'])){
		$pId=$_GET['pId'];
		if(!is_numeric($pId)){
			header("Location:index.php");
			exit;
		}
		$pageTemplateSql="select template from fe_menu where id={$pId}";
		$template=_fetch($pageTemplateSql,"array")[0]['template'];
		$template_url=THEME_NAME."/template/";
		require_once($template_url.$template);
	}
?>