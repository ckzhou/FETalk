<?php 
/*
*实现@提醒功能
*/

class mention{

	/*
	*构造函数，保存发起提醒的用户id以及话题id
	*@param $from_uid
	*@param $from_topicId
	*/
	function __construct($from_uid,$from_topicId,$from_replyId=null){
		$this->from_uid=$from_uid;
		$this->from_topicId=$from_topicId;
		if($from_replyId){
			$this->from_replyId=$from_replyId;
		}
	}
	
	/*
	*用正则从帖子内容提取@信息并保存到数据库
	*@param $text->帖子的正文内容
	*/
	public function save_mention($text){
		$pattern='/@(\w(\d|_|\w){2,11})/';
		$result=preg_match_all($pattern,$text,$out,PREG_PATTERN_ORDER);
		if($result>0){	//文本中匹配出基本的@信息
			$to_users=$out[1];
			$to_uid=array();
			foreach($to_users as $user){	//检索有效的@信息
				$len=strlen($user);
				for($i=3;$i<=$len;$i++){	//检索有效的用户名
					$username=substr($user,0,$i);
					$userExist=_fetch("SELECT id FROM ck_user WHERE username='{$username}'",'array');
					if(is_array($userExist)){	//检索到有效的用户名
						//将有效的@信息替换为该用户的主页链接，待完成。。。。。。，暂定，先实现最基本的版本
						$uid=$userExist[0]['id'];
						if(!in_array($uid,$to_uid)){	//排除掉重复@和允许@多人
							$to_uid[]=$uid;
							break;
						}
					}
				}
			}
			if(is_array($to_uid)){	//如果文本中存在有效的@信息
				foreach($to_uid as $uid){
					if(isset($this->from_replyId)){	//如果@信息来自话题下面的某条回复
						$isInserted=_insert("INSERT INTO ck_mention (from_uid,to_uid,topic_id,reply_id) VALUES({$this->from_uid},{$uid},{$this->from_topicId},{$this->from_replyId})");
					}
					else{	//如果@信息来自话题的文本
						$isInserted=_insert("INSERT INTO ck_mention (from_uid,to_uid,topic_id) VALUES({$this->from_uid},{$uid},{$this->from_topicId})");
					}
				}
			}
			else{	//文本中不存在有效的@信息
				return ;
			}
		}
	}
}
?>