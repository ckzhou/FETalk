<?php 
/*
*ʵ��@���ѹ���
*/

class mention{

	/*
	*���캯�������淢�����ѵ��û�id�Լ�����id
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
	*�����������������ȡ@��Ϣ�����浽���ݿ�
	*@param $text->���ӵ���������
	*/
	public function save_mention($text){
		$pattern='/@(\w(\d|_|\w){2,11})/';
		$result=preg_match_all($pattern,$text,$out,PREG_PATTERN_ORDER);
		if($result>0){	//�ı���ƥ���������@��Ϣ
			$to_users=$out[1];
			$to_uid=array();
			foreach($to_users as $user){	//������Ч��@��Ϣ
				$len=strlen($user);
				for($i=3;$i<=$len;$i++){	//������Ч���û���
					$username=substr($user,0,$i);
					$userExist=_fetch("SELECT id FROM ck_user WHERE username='{$username}'",'array');
					if(is_array($userExist)){	//��������Ч���û���
						//����Ч��@��Ϣ�滻Ϊ���û�����ҳ���ӣ�����ɡ��������������ݶ�����ʵ��������İ汾
						$uid=$userExist[0]['id'];
						if(!in_array($uid,$to_uid)){	//�ų����ظ�@������@����
							$to_uid[]=$uid;
							break;
						}
					}
				}
			}
			if(is_array($to_uid)){	//����ı��д�����Ч��@��Ϣ
				foreach($to_uid as $uid){
					if(isset($this->from_replyId)){	//���@��Ϣ���Ի��������ĳ���ظ�
						$isInserted=_insert("INSERT INTO ck_mention (from_uid,to_uid,topic_id,reply_id) VALUES({$this->from_uid},{$uid},{$this->from_topicId},{$this->from_replyId})");
					}
					else{	//���@��Ϣ���Ի�����ı�
						$isInserted=_insert("INSERT INTO ck_mention (from_uid,to_uid,topic_id) VALUES({$this->from_uid},{$uid},{$this->from_topicId})");
					}
				}
			}
			else{	//�ı��в�������Ч��@��Ϣ
				return ;
			}
		}
	}
}
?>