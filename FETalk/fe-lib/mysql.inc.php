<?php
	/*=====连接MySQL=====*/
	function _connect(){
		if($link=@mysql_connect(SERVER,USERNAME,PASSWORD)){
			mysql_select_db(DATABASE,$link);
			mysql_query("set names utf8");
		}
		else{
			exit("<h1>数据库连接出错</h1>");
		}
	}
	
	_connect();//调用函数连接数据库
	
	/*=====从结果集中取出数据=====*/
	/*=====$mode="int"返回记录条数，$mode="array"返回实际数据=====*/
	function _fetch($sql,$mode="array"){
		$resultSet=mysql_query($sql);
		$rows=mysql_num_rows($resultSet);
		if($mode=="int"){
			return $rows;
		}
		else if($mode=="array"){
			if($rows==1){
				$resultArr[]=mysql_fetch_assoc($resultSet);
				return $resultArr;
			}
			else if($rows>1){
				while($arr=mysql_fetch_assoc($resultSet)){
					$resultArr[]=$arr;
				}
				return $resultArr;
			}
		}
	}
	
	/*=====插入数据=====*/
	/*=====$sql是相应的SQL语句=====*/
	function _insert($sql){
		if(mysql_query($sql)){
			return mysql_affected_rows();
		}
		else{
			exit;
		}
	}
	
	/*=====删除数据=====*/
	/*=====$sql是相应的SQL语句=====*/
	function _delete($sql){
		if(mysql_query($sql)){
			return mysql_affected_rows()+1;
		}
		else{
			exit("<h1>删除数据失败</h1>");
		}
	}
	
	/*=====更新数据=====*/
	/*=====$sql是相应的SQL语句=====*/
	function _update($sql){
		if(mysql_query($sql)){
			return mysql_affected_rows();
		}
		else{
			exit("<h1>修改数据出错</h1>");
		}
	}
?>