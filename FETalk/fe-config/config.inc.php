<?php  
	if(CALL_TOKEN!="f2ecreek"){
		header("Location:http://localhost/ckcom");
		exit;
	}
	//the config information about mysql
	define("SERVER","localhost");
	define("USERNAME","root");
	define("PASSWORD","root");
	define("DATABASE","fetalk");
	
	//the config information about root path 
	define("DOCUMENT_ROOT",$_SERVER["SERVER_NAME"]."/fetalk");
	
	//the config information about display of the selected theme
	$config=new config();
	$themeName=$config->getConfig('theme');
	
	define("THEME_NAME",$themeName);
?>