<?php 
/*
*��config.xml����ȡ������Ϣ�����޸�config.xml�������Ϣ
*/

class config{
	
	/*
	*���캯��������config.xml��·�����Ҽ����ļ�
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
	*��ȡ��Ϣ
	*@param $tagName XML��ǩ����
	*/
	public function getConfig($tagName){
		$tag=$this->root->getElementsByTagName($tagName);
		foreach($tag as $textNode){
			return $textNode->nodeValue;
		}
	}
	
	/*
	*�޸���Ϣ
	*@param $tagName	XML��ǩ����
	*@param $newValue	�����µ�ֵ
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