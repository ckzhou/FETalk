<?php 
	if(CALL_TOKEN!="f2ecreek"){
		header("Location:http://www.ck.com/ckcom");
		exit;
	}
	header("Content-type:text/html;charset=utf-8");
	define("ROOT",dirname(__FILE__));
	set_include_path(get_include_path().PATH_SEPARATOR.ROOT."\\fe-config".PATH_SEPARATOR.ROOT."\\fe-lib".PATH_SEPARATOR.ROOT."\\fe-content\\fe-theme");
	session_start();
	if(!isset($_SESSION['user_index'])){	//初次访问且处于未登录状态
		$_SESSION['user_index']=session_id();
	}
	require_once("functions.php");
	require_once('config.class.php');
	require_once("config.inc.php");
	require_once("mysql.inc.php");
	require_once("fe-api.php");
	require_once("xsshtml.class.php");
	require_once("Parsedown.php");
	require_once("mention.class.php");
	require_once("mail.class.php");
?>