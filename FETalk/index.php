<?php 
	/*this file is the entrance of application ,it will display the homepage through calling the related file in the selected theme*/
	define("CALL_TOKEN","f2ecreek");
	require_once("fe-include.php");
	$theme=THEME_NAME;
	require_once($theme."/index.php");
?>

