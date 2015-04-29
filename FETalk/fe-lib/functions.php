<?php 
	if(CALL_TOKEN!="f2ecreek"){
		header("Location:http://www.ck.com/ckcom");
		exit;
	}
	
	/**
	 *获取页面路径
	 *@param $obj 代表页面
	 */
	function _path($obj){
		$base="http://".DOCUMENT_ROOT;
		switch($obj){
			case "home":
				return $base;
			case "theme_url":
				$path="/fe-content/fe-theme/".THEME_NAME;
				break;
			case "login":
				$path="/fe-login.php";
				break;
			case "loginout":
				$path="/fe-action.php?act=out";
				break;
			case "set":
				$path="/fe-setting.php?set_type=basic";
				break;
			case "register":
				$path="/fe-register.php";
				break;
			case "template":
				$path="/fe-content/fe-theme/".THEME_NAME."/template";
				break;
			default:
				break;
		}
		$path=$base.$path;
		return $path;
	}
	
	/**
	 *检查普通用户以及管理员的登录信息
	 *@param $role 用户角色 $role='user'检查网站普通用户 $role='admin'检查网站管理员
	 */
	
	function _checkLogin($role="user"){
		if($role=="user"){
			$id="uid";
			$name="username";
		}
		else if($role=="admin"){
			$id="adminId";
			$name="admin";
		}
		$table="fe_".$role;
		if(isset($_SESSION[$id])){
			if($id==='uid'){	//如果是网站用户，检验是否已被拉黑
				$email=_fetch("SELECT email FROM fe_user WHERE uniqId='{$_SESSION[$id]}'","array")[0]['email'];
				if(checkDefriend($email)){	//如果已经被拉黑
					_logout();	//登出账号
					return false;
				}
			}
			return true;
		}
		else if(isset($_COOKIE[$id])){
			$uid=inject_check($_COOKIE[$id]);
			$username=inject_check($_COOKIE[$name]);
			$user=_fetch("SELECT id,email FROM {$table} WHERE uniqId='{$uid}' AND username='{$username}'","array");
			if(is_array($user)){	//身份信息验证通过
				$user=$user[0];
				$_SESSION['user_index']=$user['id'];
				$_SESSION[$id]=$uid;
				$_SESSION[$name]=$username;
				return true;
			}
		}
		return false;
	}
	
	/**
	 *包含网页头部
	 */
	function get_header(){
		require_once(THEME_NAME."/header.php");
	}

	/**
	 *包含网页页脚
	 */
	function get_footer(){
		require_once(THEME_NAME."/footer.php");
	}
	
	/**
	 *生成csrfToken
	 */
	function _create_token(){
		$token=sha1(uniqid(mt_rand()));
		if(isset($_SESSION['token'])&&is_array($_SESSION['token'])){	//如果session中的token数组已经创建
			$_SESSION['token'][]=$token;
		}
		else{	//初次存入token,初始化session中的token为一个数组，方便存入后续存入多个token以防用户同时打开多个表单页面
			$_SESSION['token']=array();
			$_SESSION['token'][]=$token;
		}
		return $token;
	}
	
	/**
	 *验证token,确认不是跨站请求伪造
	 *@param $token 客户端传递过来的token值
	 */
	function checkToken($token){
		$key=array_search($token,$_SESSION['token'],true);
		if($key===false){	//token验证失败
			exit('http error');
		}
		else{	//验证成功，销毁session中的当前token
			unset($_SESSION['token'][$key]);
			return true;
		}
	}
	
	/**
	 *登出账号
	 *@param $role代表用户角色 $role="user"代表网站普通用户，$role="admin"代表网站管理员
	 */
	function _logout($role="user"){
		if($role=="user"){
			$id="uid";
			$name="username";
			$redirect="index.php";
		}
		else if($role=='admin'){
			$id="adminId";
			$name="admin";
			$redirect="login.php";
		}
		if(isset($_COOKIE[$id])){
			setcookie($id,'',time()-3600,'/','localhost');
		}
		if(isset($_COOKIE[$name])){
			setcookie($name,'',time()-3600,'/','localhost');
		}
		session_destroy();
		header("Location:{$redirect}");
	}
	
	/** 
	 *防御sql注入
	 *@param $item 用户输入项
	 */
	function inject_check($item){
		/**
		 *1.过滤掉select,insert,update,delete,drop,alter,union,=等关键字
		 *2.转义'_','%',mysql_real_escape_string并不处理'_'和'%'字符
		 *3.使用mysql_real_escape_string进行处理
		 *4.对PHP进行正确的配置
		 */
		if(get_magic_quotes_gpc()){
			$item=stripslashes($item);
		}
		$not_allow=array('select','insert','update','delete','drop','alter','union','=');
		foreach($not_allow as $kw){
			$pattern=$kw;
			$item=eregi_replace($pattern,'',$item);
		}
		$item=str_replace('_','\_',$item);
		$item=str_replace('%','\%',$item);
		$item=mysql_real_escape_string($item);
		return $item;
	}
	
	/**
	 *迭代GET和POST数组的回调函数，对其每一项进行过滤处理
 	 */
	function input_walk(&$item,$key){
		if(!$item){
			return;
		}
		else{
			$item=inject_check($item);
			$xss=new XssHtml($item);
			$item=$xss->getHtml();
		}
	}
	
	/**
	 *对用户上传的图片文件进行验证
	 */
	function validate_image(){
		/**
		 *1.检查文件是否是通过post上传的,is_uploaded_file()
		 *2.检查文件是否是一张真正的图片
		 *3.检查图片的格式是否符合要求
		 *4.检查图片的大小是否不超过1M
		 *5.对图片进行裁剪并生成缩略图
		 *6.将图片移动至指定目录
		 */
		$image=$_FILES['face']['tmp_name'];
		$error_array=array();
		if(is_uploaded_file($image)){	//如果上传了头像
			if($size=getimagesize($image)){	//如果上传的确是图片文件
				if(isset($size['mime'])){
					$format_allow=array('image/png','image/jpeg','image/gif');
					$mime=$size['mime'];
					if(in_array($mime,$format_allow)){	//如果图片格式正确
						if($_FILES['face']['size']<=1048576){	//如果图片大小小于1M
							$file_name=save_image($image,$size);
							if($file_name!==null){
								return $file_name;
							}
							else{
								return;
							}
						}
						else{	//图片大于1M
							$error_array['size']='图片文件不要大于1M';
						}
					}
					else{	//图片格式不正确
						$error_array['format']='图片格式有误，只支持JPEG,PNG,GIF格式';
					}
				}
			}
			else{	//上传非法文件
				$error_array['type']='上传的文件类型有误';
			}
		}
		else{	//没有上传头像
			$error_array['exsist']='请先选择要上传的头像';
		}
		if(count($error_array)>0){
			$json=json_encode($error_array);
			$json_path=dirname(dirname(__FILE__)).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
			$json_file=fopen($json_path,'wb');
			fwrite($json_file,$json);
			header('Location:fe-setting.php?set_type=face');
			exit;
		}
	}
	
	/**
	 *对上传的头像图片进行格式转换，生成缩略图，并且存储
	 *@param array $size 图片的尺寸信息
	 */
	function save_image($image,$size){
		list($width,$height)=$size;
		$func_name='imagecreatefrom'.explode('/',$size['mime'])[1];
		$source=$func_name($image);
		$max_png=imagecreatetruecolor(96,96);
		$big_png=imagecreatetruecolor(48,48);
		$min_png=imagecreatetruecolor(32,32);
		imagecopyresized($max_png,$source,0,0,0,0,96,96,$width,$height);
		imagecopyresized($big_png,$source,0,0,0,0,48,48,$width,$height);
		imagecopyresized($min_png,$source,0,0,0,0,32,32,$width,$height);
		$file_name=uniqid(mt_rand(),false).'.png';
		$path=dirname(dirname(__FILE__)).'/fe-content/fe-face/';
		if(imagepng($max_png,$path.'max_'.$file_name)&&imagepng($big_png,$path.'big_'.$file_name)&&imagepng($min_png,$path.$file_name)){
			return $file_name;
		}
		else{
			return ;
		}
	}
	
	/**
	 *将帖子加入收藏，前端请用Ajax处理,请求方式为GET方式
	 */
	function collect_topic(){
		array_walk($_GET,'input_walk');
		$user_id=$_SESSION['user_index'];
		$topic_id=intval($_GET['t']);
		$message_array=array();
		if(_fetch("SELECT id FROM fe_collect WHERE uId={$user_id} AND tId={$topic_id}","int")==1){
			_delete("DELETE FROM fe_collect WHERE uId={$user_id} AND tId={$topic_id}");
			$message_array['message']='uncollect_topic';
			$message_array['success']=1;
		}
		else{
			_insert("INSERT INTO fe_collect (uId,tId) VALUES({$user_id},{$topic_id})");
			$message_array['message']='collect_topic';
			$message_array['success']=1;
		}
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	}
	
	/** 
	 *给帖子点赞，前端请用Ajax处理，请求方式为GET方式
	 */
	 function vote_topic(){
		array_walk($_GET,'input_walk');
		$user_id=$_SESSION['user_index'];
		$topic_id=intval($_GET['t']);
		$message_array=array();
		$message_array['message']='vote';
		if(_fetch("SELECT id FROM fe_vote WHERE user_id={$user_id} AND topic_id={$topic_id}","int")==1){
			$message_array['success']=0;
		}
		else{
			_insert("INSERT INTO fe_vote (topic_id,user_id) VALUES({$topic_id},{$user_id})");
			$message_array['success']=1;
		}
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	 }
	 
	/**
	 *给回复点赞，前端请用Ajax处理，请求方式为GET方式
	 */
	 function agree_reply(){
		array_walk($_GET,'input_walk');
		$user_id=$_SESSION['user_index'];
		$reply_id=$_GET['r'];
		$message_array=array();
		$message_array['message']='agree';
		if(_fetch("SELECT id FROM fe_agreement WHERE user_id={$user_id} AND reply_id={$reply_id}","int")==1){	//已经赞了此条回复
			$message_array['success']=0;
		}
		else{	//还未赞此条回复
			_insert("INSERT INTO fe_agreement (reply_id,user_id) VALUES({$reply_id},{$user_id})");
			_update("UPDATE fe_reply SET agree_times=agree_times+1 WHERE id={$reply_id}");
			$message_array['success']=1;
		}
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	 }
	 
	/**
	 *关注某人，前端请用Ajax处理，请求方式为GET方式
	 */
	 function concern(){
		$csrfToken=$_GET['token'];
		$key=checkToken($csrfToken);	//验证Token
		array_walk($_GET,'input_walk');
		$active_user_id=$_SESSION['user_index'];
		$passive_user_id=$_GET['u'];
		$message_array=array();
		if(_fetch("SELECT id FROM fe_concern WHERE active_id={$active_user_id} AND passive_id={$passive_user_id}","int")==1){
			_delete("DELETE FROM fe_concern WHERE active_id={$active_user_id} AND passive_id={$passive_user_id}");	//取消关注
			$message_array['message']='unconcern';
			$message_array['success']=1;
		}
		else{
			_insert("INSERT INTO fe_concern (active_id,passive_id) VALUES({$active_user_id},{$passive_user_id})");	//增加关注
			$message_array['message']='concern';
			$message_array['success']=1;
		}
		$message_array['token']=_create_token();
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	 }
	 
	/**
	 *发送私信，前端请用Ajax处理,POST方式
	 */
	 function send_letter(){
		$csrfToken=$_POST['token'];
		checkToken($csrfToken);	//验证Token
		array_walk($_POST,'input_walk');
		$from_uid=$_SESSION['user_index'];
		$to_uid=$_POST['user_id'];	//收信人的id
		$content=$_POST['content'];
		$message_array=array();
		$message_array['message']='send_letter';
		$time=time();
		$query="INSERT INTO fe_letter (from_uid,to_uid,cTime,content,owner_id) VALUES({$from_uid},{$to_uid},{$time},'{$content}',{$from_uid}),({$from_uid},{$to_uid},{$time},'{$content}',{$to_uid})";
		_insert($query);
		$message_array['success']=1;
		$message_array['token']=_create_token();
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	 }
	 
	 
	/**
	 *保存好友动态 好友动态最多保留一页的数量
	 *@param $topic_id 被操作的话题的id
	 *@param $create_type 表示对话题的操作，只能是create_topic(创建话题)，create_reply（发表回复)之一
	 */
	function save_dynamic($topic_id,$create_type){
		$config=new config();
		$topic_page=$config->getConfig('topicPage');
		$user_id=$_SESSION['user_index'];
		$query="SELECT active_id FROM fe_concern WHERE passive_id={$user_id}";
		$concern_array=_fetch($query,'array');
		if(is_array($concern_array)){	//如果有人关注你
			foreach($concern_array as $concern){	//给所有关注你的人增加一条好友动态
				$active_id=$concern['active_id'];
				_update("UPDATE fe_user SET dynamic_count=dynamic_count+1 WHERE id={$active_id} AND dynamic_count<{$topic_page}");
			}
			_insert("INSERT INTO fe_dynamics (user_id,topic_id,type) VALUES({$user_id},{$topic_id},'{$create_type}')");
		}
		else{	//如果没人关注，退出函数
			return;
		}
	}
	 
	/**
	 *提取当前用户已关注好友的动态,动态最多保存一页的数量
	 */
	function get_dynamics(){
		$user_id=$_SESSION['user_index'];
		$dynamic_count=_fetch("SELECT dynamic_count FROM fe_user WHERE id={$user_id}","array")[0]['dynamic_count']; //获取好友动态条数
		if($dynamic_count>0){	//如果存在好友动态
			$query="SELECT dynamics.* FROM fe_user AS user INNER JOIN fe_concern AS concern ON user.id=concern.active_id INNER JOIN fe_dynamics AS dynamics ON concern.passive_id=dynamics.user_id ORDER BY dynamics.id DESC LIMIT {$dynamic_count}";
			$dynamics_array=array();
			$keys=array();
			$dynamics=_fetch($query,'array');
			_update("UPDATE fe_user SET dynamic_count=0 WHERE id={$user_id}");
			foreach($dynamics as $item){
				$item_array=array();
				$topic_id=$item['topic_id'];
				$type=$item['type'];
				if($type==='create_reply'){
					if(in_array($topic_id,$keys)){
						foreach($dynamics_array as $dynamic){
							if($dynamic['topic_id']===$topic_id&&$dynamic['type']==='create_reply'){
								if(!in_array($item['user_id'],$dynamic['user_id'])){
									$dynamic['user_id'][]=$item['user_id'];
								}
								break;
							}
						}
					}
					else{
						$keys[]=$topic_id;
						$item_array['user_id'][]=$item['user_id'];
						$item_array['topic_id']=$item['topic_id'];
						$item_array['type']=$item['type'];
						$dynamics_array[]=$item_array;
					}
				}
				else if($type==='create_topic'){
					$item_array['author_id']=$item['user_id'];
					$item_array['topic_id']=$item['topic_id'];
					$item_array['type']=$item['type'];
					$dynamics_array[]=$item_array;
				}
			}
			return $dynamics_array;
		}
		else{
			return;
		}
	}
	
	/**
	 *获取用户所有的未读提醒的详细信息
	 */
	function get_mention(){
		$user_id=$_SESSION['user_index'];
		$not_read=array('at'=>array('number'=>0,'items'=>array()),'letter'=>array('number'=>0,'items'=>array()));
		$query_at="SELECT id,from_uid,topic_id,reply_id FROM fe_mention WHERE to_uid={$user_id} AND have_read='not' ORDER BY id DESC";
		$at_arr=_fetch($query_at,'array');
		if(is_array($at_arr)){	//存在未读的at消息
			$not_read['at']['number']=count($at_arr);
			foreach($at_arr as $at){
				$item=array();
				$item['id']=$at['id'];
				$item['from_uid']=$at['from_uid'];
				$item['from_user']=get_username($at['from_uid']);
				$item['topic_id']=$at['topic_id'];
				$item['topic_title']=get_title($at['topic_id']);
				if($at['reply_id']){
					$item['reply_text']=get_reply_text($at['reply_id']);
				}
				else{
					$item['topic_text']=get_topic_text($at['topic_id']);
				}
				$not_read['at']['items'][]=$item;
			}
			_update("UPDATE fe_mention SET have_read='yes' WHERE to_uid={$user_id} AND have_read='not'");//将未读的at提醒更改为已读
		}
		$query_letter="SELECT from_uid,cTime,content FROM fe_letter WHERE to_uid={$user_id} AND owner_id={$user_id} AND have_read='not' ORDER BY id DESC";
		$letter_arr=_fetch($query_letter,'array');
		if(is_array($letter_arr)){	//存在未读的私信消息
			$not_read['letter']['number']=count($letter_arr);
			foreach($letter_arr as $letter){
				$item=array();
				$item['from_uid']=$letter['from_uid'];
				$item['from_user']=get_username($letter['from_uid']);
				$item['content']=$letter['content'];
				$item['created_time']=$letter['cTime'];
				$not_read['letter']['items'][]=$item;
			}
			_update("UPDATE fe_letter SET have_read='yes' WHERE to_uid={$user_id} AND have_read='not'");//将未读的私信消息更爱为已读
		}
		return $not_read;
	}
	
	/**
	 *获取用户所有的被@历史记录
	 */
	function get_all_at(){
		$user_id=$_SESSION['user_index'];
		$all_at=array('number'=>0,'items'=>array());
		$query_at="SELECT id,from_uid,topic_id,reply_id FROM fe_mention WHERE to_uid={$user_id} ORDER BY id";
		$at_arr=_fetch($query_at);
		if(is_array($at_arr)){	//存在被@的记录
			$all_at['number']=count($at_arr);
			foreach($at_arr as $at){
				$item=array();
				$item['id']=$at['id'];
				$item['from_uid']=$at['from_uid'];
				$item['from_user']=get_username($at['from_uid']);
				$item['topic_id']=$at['topic_id'];
				$item['topic_title']=get_title($at['topic_id']);
				if($at['reply_id']){
					$item['reply_text']=get_reply_text($at['reply_id']);
				}
				else{
					$item['topic_text']=get_topic_text($at['topic_id']);
				}
				$all_at['items'][]=$item;
			}
		}
		return $all_at;
	}
	
	/**
	 *获取用户的私信对话记录
	 */
	function get_all_letter(){
		$user_id=$_SESSION['user_index'];
		$all_deliver=array('number'=>0,'items'=>array());
		$query_deliver="SELECT cTime,to_uid,content FROM fe_letter WHERE from_uid={$user_id} AND owner_id={$user_id}";
		$deliver_arr=_fetch($query_deliver,'array');
		$all_receive=array('number'=>0,'items'=>array());
		$query_receive="SELECT cTime,from_uid,content FROM fe_letter WHERE to_uid={$user_id} AND owner_id={$user_id}";
		$receive_arr=_fetch($query_receive,'array');
		if(!is_array($deliver_arr)&&!$receive_arr){	//没有任何的私信记录
			return ;
		}
		else{
			$dialogue=array();
			if(is_array($deliver_arr)){	//如果你发出过私信,将其中接收用户相同的私信整理为一个对话
				foreach($deliver_arr as $deliver){
					$dialogue[$deliver['to_uid']][]=$deliver;
				}
			}
			if(is_array($receive_arr)){	//如果你收到过私信，将其中的私信整合到对应的对话中
				foreach($receive_arr as $receive){
					$dialogue[$receive['from_uid']][]=$receive;
				}
			}
			foreach($dialogue as &$item){	//对所有的对话按照创建时间进行排序
				arsort($item);
			}
			arsort($dialogue);	//对私信按照时间进行排序
			return $dialogue;
		}
	}
	
	/**
	 *获取和某人对话的私信记录
	 */
	function get_detail_dialogue(){
		$self_id=$_SESSION['user_index'];
		if(isset($_GET['f'])&&is_numeric($_GET['f'])){	//是否存在sql注入，待进一步查阅文档
			$others_id=$_GET['f'];
			$query_deliver="SELECT cTime,to_uid,content,id FROM fe_letter WHERE from_uid={$self_id} AND to_uid={$others_id} AND owner_id={$self_id}";
			$deliver_arr=_fetch($query_deliver,'array');
			$query_receive="SELECT cTime,from_uid,content,id FROM fe_letter WHERE from_uid={$others_id} AND to_uid={$self_id} AND owner_id={$self_id}";
			$receive_arr=_fetch($query_receive,'array');
			if(is_array($deliver_arr)&&is_array($receive_arr)){	//如果双方相互有私信往来
				$detail_dialogue=array_merge($deliver_arr,$receive_arr);
			}
			else if(is_array($deliver_arr)){	//如果只有自己单方面的发出私信
				$detail_dialogue=$deliver_arr;
			}
			else if(is_array($receive_arr)){	//如果只有对方单方面的发私信给自己
				$detail_dialogue=$receive_arr;
			}
			else{	//如果双方没有任何私信往来
				return ;
			}
			arsort($detail_dialogue);	//对私信按照发送时间进行排序
			return $detail_dialogue;
		}
		else{
			return;
		}
	}
	
	/**
	 *删除和某个用户的所有对话
	 */
	function del_dialogue(){
		$token=$_GET['token'];
		checkToken($token);	//验证token
		$self_id=$_SESSION['user_index'];
		$message_array=array();
		$message_array['message']='del_dialogue';
		if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$others_id=$_GET['u'];
			$del_deliver="DELETE FROM fe_letter WHERE from_uid={$self_id} AND to_uid={$others_id} AND owner_id={$self_id}";
			_delete($del_deliver);
			$del_receive="DELETE FROM fe_letter WHERE from_uid={$others_id} AND to_uid={$self_id} AND owner_id={$self_id}";
			_delete($del_receive);
			$message_array['success']=1;
		}
		else{
			$message_array['success']=0;
		}
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	}
	
	/**
	 *删除某一条私信
	 */
	function del_letter(){
		$token=$_GET['token'];
		checkToken($token);	//验证token
		$message_array=array();
		$message_array['message']='del_letter';
		if(isset($_GET['l'])&&is_numeric($_GET['l'])){
			$letter_id=$_GET['l'];
			$self_id=$_SESSION['user_index'];
			$del_letter="DELETE FROM fe_letter WHERE id={$letter_id} AND owner_id={$self_id}";
			_delete($del_letter);
			$message_array['success']=1;
		}
		else{
			$message_array['success']=0;
		}
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	}
	
	/**
	 *删除一条@历史记录
	 */
	function del_at(){
		$token=$_GET['token'];
		checkToken($token);
		$message_array=array();
		$message_array['message']='del_at';
		if(isset($_GET['a'])&&is_numeric($_GET['a'])){
			$at_id=$_GET['a'];
			$self_id=$_SESSION['user_index'];
			$del_at="DELETE FROM fe_mention WHERE id={$at_id} AND to_uid={$self_id}";
			_delete($del_at);
			$message_array['success']=1;
		}
		else{
			$message_array['success']=0;
		}
		$json=json_encode($message_array);
		header('Content-type:text/json');
		echo $json;
		exit;
	}
	
	/**
	 *获取最近发布的帖子,默认为20条
	 */
	function get_recent_topic($number=20){
		$query_topic="SELECT title,nodeId,uId,cTime FROM fe_topic ORDER BY cTime DESC LIMIT {$number}";
		$topic_arr=_fetch($query_topic,"array");
		$topic=array();
		if(is_array($topic_arr)){	//如果帖子存在
			foreach($topic_arr as $item){
				$topic_item=array();
				$user_id=$item['uId'];
				$author=get_username($user_id);
				$node_id=$item['nodeId'];
				$node=get_node($node_id);
				$time=date("Y-m-d H:i",$item["cTime"]);
				$topic_item['title']=$item['title'];
				$topic_item['node']=$node;
				$topic_item['author']=$author;
				$topic_item['time']=$time;
				$topic[]=$topic_item;
			}
		}
		return $topic;
	}
	
	/**
	 *按照关键字搜索相关帖子
	 */
	function search_topic(){
		if(isset($_GET['title'])){
			array_walk($_GET,'input_walk');
			$message_array=array();
			$message_array['message']='searchTopic';
			$title=$_GET['title'];
			$query_topic="SELECT title,nodeId,uId,cTime FROM fe_topic WHERE title LIKE '%{$title}%' ORDER BY cTime DESC";
			$topic=array();
			$topic_arr=_fetch($query_topic,"array");
			if(is_array($topic_arr)){	//如果搜索到了相关帖子
				foreach($topic_arr as $key=>$item){
					$topic_item=array();
					$user_id=$item['uId'];
					$author=get_username($user_id);
					$node_id=$item['nodeId'];
					$node=get_node($node_id);
					$time=date("Y-m-d H:i",$item["cTime"]);
					$topic_item['title']=$item['title'];
					$topic_item['node']=$node;
					$topic_item['author']=$author;
					$topic_item['time']=$time;
					$topic[]=$topic_item;
				}
				$message_array['success']=1;
				$message_array['result']=$topic;
			}
			else{
				$message_array['success']=0;
			}
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	 
	 
	/**
	 *获取最近注册的用户
	 *@param $number 用户数量，默认为10个
	 */
	function get_recent_user($number=10){
		$query_user="SELECT user.username,user.email,user.regTime,COUNT(topic.id) AS counts FROM fe_user AS user LEFT JOIN fe_topic AS topic ON user.id=topic.uId GROUP BY user.id ORDER BY user.regTime DESC LIMIT {$number}";
		$user_arr=_fetch($query_user,"array");
		$user=array();
		if(is_array($user_arr)){	//如果用户存在
			foreach($user_arr as $item){
				$user_item=array();
				$time=date("Y-m-d H:i",$item['regTime']);
				$user_item['name']=$item['username'];
				$user_item['email']=$item['email'];
				$user_item['time']=$time;
				$user_item['number']=$item['counts'];
				$user[]=$user_item;
			}
		}
		return $user;
	}
	
	/**
	 *获取目前所有可用的主题
	 */
	function get_all_theme(){
		$path=dirname(dirname(__FILE__))."\\fe-content\\fe-theme\\";
		$theme=array();
		$directory=dir($path);
		while(($entry=$directory->read())!==false){
			if($entry!="."&&$entry!=".."&&is_dir($path.$entry)){	//如果是目录，则代表一个主题
				$theme[]=$entry;
			}
		}
		return $theme;
	}
	
	/**
	 *获取所有的页面
	 */
	function get_all_page(){
		$query_page="SELECT pName,template FROM fe_menu";
		$page_arr=_fetch($query_page,"array");
		$page=array();
		if(is_array($page_arr)){	//如果存在页面
			foreach($page_arr as $item){
				$page_item=array();
				$page_item['name']=$item['pName'];
				$page_item['template']=$item['template'];
				$page[]=$page_item;
			}
		}
		return $page;
	}
	
	/**
	 *获取所有的页面模板
	 */
	function get_page_template(){
		$template=array();
		$path=dirname(dirname(__FILE__))."\\fe-content\\fe-theme\\".THEME_NAME."\\template\\";
		$files=dir($path);
		while(($entry=$files->read())!==false){
			if(is_file($path.$entry)){//如果是文件，则是一个页面模板文件
				$template[]=$entry;
			}
		}
		return $template;
	}
	
	/**
	 *添加新的节点分类,前端用Ajax处理,GET方式
	 */
	function add_category(){
		if(isset($_GET['category'])){
			$token=$_GET['token'];
			checkToken($token);	//验证token
			array_walk($_GET,'input_walk');
			$message_array=array();
			$message_array['message']="new category";
			$category=$_GET['category'];
			_insert("INSERT INTO fe_category (cName) VALUES('{$category}')");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *修改分类的名称，前端用Ajax处理，GET方式
	 */
	function update_category(){
		if(isset($_GET['newCategory'])&&isset($_GET['oldCategory'])){
			array_walk($_GET,'input_walk');
			$message_array=array();
			$message_array['message']='updateCategory';
			$oldCategory=$_GET['oldCategory'];
			$newCategory=$_GET['newCategory'];
			_update("UPDATE fe_category SET cName='{$newCategory}' WHERE cName='{$oldCategory}'");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type;text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *添加新的节点，前端用Ajax方式处理，GET方式
	 */
	function add_node(){
		if(isset($_GET['newNode'])&&isset($_GET['category'])){
			array_walk($_GET,'input_walk');
			$message_array=array();
			$message_array['message']='addNode';
			$newNode=$_GET['newNode'];
			$category=$_GET['category'];
			_insert("INSERT INTO fe_node (nName,cId) VALUES('{$newNode}',{$category})");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *更新已经存在的节点，前端用Ajax处理，GET方式
	 */
	function update_node(){
		if(isset($_GET['newNode'])&&isset($_GET['oldNode'])&&isset($_GET['category'])){
			array_walk($_GET,'input_walk');
			$message_array=array();
			$message_array['message']='updateNode';
			$newNode=$_GET['newNode'];
			$oldNode=$_GET['oldNode'];
			$category_id=$_GET['category'];
			_update("UPDATE fe_node SET nName='{$newNode}',cId={$category_id} WHERE nName='{$oldNode}'");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *删除垃圾帖子，前端用Ajax处理,GET方式
	 */
	function delete_topic(){
		if(isset($_GET['topic'])){
			array_walk($_GET,'input_walk');
			$topic=$_GET['topic'];
			$topic_id=_fetch("SELECT id FROM fe_topic WHERE title='{$topic}'","array")[0]['id'];
			$message_array=array();
			$message_array['message']='deleteTopic';
			_delete("DELETE FROM fe_topic WHERE id={$topic_id}");
			_delete("DELETE FROM fe_reply WHERE tId={$topic_id}");
			_delete("DELETE FROM fe_collect WHERE tId={$topic_id}");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *搜索用户，前端用Ajax处理，GET方式
	 */
	function search_user(){
		if(isset($_GET['user'])){
			array_walk($_GET,'input_walk');
			$username=$_GET['user'];
			$message_array=array();
			$message_array['message']='searchUser';
			$query_user="SELECT user.username,user.email,user.regTime,COUNT(topic.id) AS counts FROM fe_user AS user LEFT JOIN fe_topic AS topic ON user.id=topic.uId WHERE username LIKE '%{$username}%' GROUP BY user.id ORDER BY user.regTime DESC";
			$user=array();
			$user_arr=_fetch($query_user,"array");
			if(is_array($user_arr)){	//搜索到了相关用户
				foreach($user_arr as $item){
					$user_item=array();
					$user_item['username']=$item['username'];
					$user_item['email']=$item['email'];
					$user_item['time']=$item['regTime'];
					$user_item['number']=$item['counts'];
					$user[]=$user_item;
				}
				$message_array['success']=1;
				$message_array['result']=$user;
			}
			else{
				$message_array['success']=0;
			}
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *检查某个用户是否已经被拉黑
	 *@param $email 用户邮箱
	 */
	function checkDefriend($email){
		$result=_fetch("SELECT defriended FROM fe_user WHERE email='{$email}'","array")[0]["defriended"];
		if($result=='not'){
			return false;
		}
		else if($result=='yes'){
			return true;
		}
	}
	
	/**
	 *拉黑或解除拉黑某个用户，前端Ajax处理，GET方式
	 *@param $action $action='yes'为拉黑 $action='not'为解除拉黑 
	 */
	function defriend($action){
		if(isset($_GET['email'])){
			array_walk($_GET,'input_walk');
			$email=$_GET['email'];
			$message_array=array();
			_update("UPDATE fe_user SET defriended='{$action}' WHERE email='{$email}'");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *更新管理员的身份验证信息，前端Ajax处理，POST方式
	 */
	function update_admin(){
		$token=$_POST['token'];
		checkToken($token);
		array_walk($_POST,'input_walk');
		$message_array=array();
		$message_array['message']='updateAdmin';
		$username=$_POST['username'];
		$password=$_POST['password'];
		if(!$username&&!$password){	//用户名密码均未填写
			$message_array['success']=0;
			$message_array['result']='empty information';
		}
		else if($password){	//填写了新密码
				if(strlen($password)<=6){	//密码长度少于6个字符
					$message_array['success']=0;
					$message_array['result']='weak password';
				}
				else{	//密码长度符合需求
					$db_password=MD5($password);
					$query_admin="UPDATE fe_admin SET password='{$db_password}'";
				}
		}
		if(!isset($message_array['success'])){	//如果前面没有检查出错误
			if($username){	//填写了新的用户名
				$_SESSION['admin']=$username;
				if(isset($query_admin)){
					$query_admin.=",username='{$username}'";
				}
				else{
					$query_admin="UPDATE fe_admin SET username='{$username}'";
				}
			}
			$query_admin.=" WHERE id={$_POST['id']}";
			_update($query_admin);
			$profile=_fetch("SELECT username,uniqId FROM fe_admin WHERE id={$_POST['id']}")[0];
			$message_array['success']=1;
			$message_array['result']=array();
			$message_array['result']['username']=$profile['username'];
			setcookie('admin',$username,time()+3600*34*30,'/','localhost',0,1);
			setcookie('adminId',$profile['uniqId'],time()+3600*24*30,'/','localhost',0,1);
		}
		$token=_create_token();
		$message_array['token']=$token;
		$json=json_encode($message_array);
		header("Content-type:text/json");
		echo $json;
		exit;
	}
	
	/**
	 *添加页面，前端Ajax方式处理,GET方式
	 */
	function add_page(){
		if(isset($_GET['page'])){
			$page=$_GET['page'];
			$template=$_GET['template'];
			if($template){	//设置了页面模板
				$query_page="INSERT INTO fe_menu (pName,template) VALUES('{$page}','{$template}')";
			}
			else{	//当前没有可用的页面模板
				$query_page="INSERT INTO fe_menu (pName) VALUES('{$page}')";
			}
			_insert($query_page);
			$message_array=array();
			$message_array['message']='addPage';
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *编辑页面名称和页面模板，前端Ajax处理，GET方式
	 */
	function edit_page(){
		if(isset($_GET['newPage'])&&isset($_GET['newTemplate'])&&isset($_GET['oldPage'])){
			$message_array=array();
			$message_array['message']='editPage';
			array_walk($_GET,'input_walk');
			$newPage=$_GET['newPage'];
			$newTemplate=$_GET['newTemplate'];
			$oldPage=$_GET['oldPage'];
			_update("UPDATE fe_menu SET pName='{$newPage}',template='{$newTemplate}' WHERE pName='{$oldPage}'");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *删除页面，不会删除页面对应的模板文件，前端用Ajax处理，GET方式
	 */
	function del_page(){
		if(isset($_GET['page'])){
			array_walk($_GET,'input_walk');
			$page=$_GET['page'];
			$message_array=array();
			$message_array['message']='deletePage';
			_delete("DELETE FROM fe_menu WHERE pName='{$page}'");
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
	
	/**
	 *更改主题，前端用Ajax处理，GET方式
	 */
	function change_theme(){
		if(isset($_GET['theme'])){
			$message_array=array();
			$message_array['message']='changeTheme';
			$theme=$_GET['theme'];
			$config=new config;
			$config->setConfig('theme',$theme);
			$message_array['success']=1;
			$json=json_encode($message_array);
			header("Content-type:text/json");
			echo $json;
			exit;
		}
	}
?>