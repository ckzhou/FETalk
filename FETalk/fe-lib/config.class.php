<?php 
/*
*从config.xml中提取配置信息或者修改config.xml里面的信息
*/

class config{
	
	/*
	*构造函数，设置config.xml的路径并且加载文件
	*/
	function __construct(){
		$path=dirname(dirname(__FILE__)).'/fe-config/config.xml';
		$this->path=$path;
		$this->doc=new DOMDocument();
		$this->doc->load($this->path);
		$this->doc->formatOutput=true;
		$this->root=$this->doc->documentElement;
	}
	
	/*
	*提取信息
	*@param $tagName XML标签名称
	*/
	public function getConfig($tagName){
		$tag=$this->root->getElementsByTagName($tagName);
		foreach($tag as $textNode){
			return $textNode->nodeValue;
		}
	}
	
	/*
	*修改信息
	*@param $tagName	XML标签名称
	*@param $newValue	待更新的值
	*/
	public function setConfig($tagName,$newValue){
		$tag=$this->root->getElementsByTagName($tagName);
		foreach($tag as $textNode){
			$textNode->nodeValue=$newValue;
		}
		$this->doc->save($this->path);
	}
}
?>