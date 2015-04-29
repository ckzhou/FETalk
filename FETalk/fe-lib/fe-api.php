<?php 
	/**
	 *获取已注册用户的数目
	 */
	function fe_userNum(){
		$userNumSql="select * from fe_user";
		$userNum=_fetch($userNumSql,"int");
		return $userNum;
	}
	
	/**
	 *获取节点数目
	 */
	function fe_nodeNum(){
		$nodeNumSql="select * from fe_node";
		$nodeNum=_fetch($nodeNumSql,"int");
		return $nodeNum;
	}
	
	/**
	 *获取主题数目
	 */
	function fe_allTopicNum(){
		$allTopicNumSql="select * from fe_topic";
		$allTopicNum=_fetch($allTopicNumSql,"int");
		return $allTopicNum;
	}
	
	/**
	 *获取所有回复的数目
	 */
	function fe_allReplyNum(){
		$allReplyNumSql="select * from fe_reply";
		$allReplyNum=_fetch($allReplyNumSql,"int");
		return $allReplyNum;
	}
	
	/**
	 *获取页面选项
	 */
	function fe_menu(){
		$menuSql="select id,pName from fe_menu";
		$menuArr=_fetch($menuSql,"array");
		return $menuArr;
	}
	
	/**
	 *根据登录状态显示对应的内容
	 */
	function fe_switcher(){
		$status=array();
		$isLogin=_checkLogin();
		if($isLogin){
			$loginout_path=_path("loginout");
			$loginout_link="<a href='".$loginout_path."'>登出</a>";
			$set_path=_path("set");
			$set_link="<a href='".$set_path."'>设置</a>";
			$status['login']=true;
			$status['path'][0]=$loginout_path;
			$status['path'][1]=$set_path;
			$status['link'][0]=$loginout_link;
			$status['link'][1]=$set_link;
		}
		else{
			$login_path=_path("login");
			$login_link="<a href='".$login_path."'>登录</a>";
			$register_path=_path("register");
			$register_link="<a href='".$register_path."'>注册</a>";
			$status['login']=false;
			$status['path'][0]=$login_path;
			$status['path'][1]=$register_path;
			$status['link'][0]=$login_link;
			$status['link'][1]=$register_link;
		}
		return $status;
	}
	
	/**
	 *获取客户端传过来的页码
	 */
	function get_page(){
		if(isset($_GET['p'])&&is_numeric($_GET['p'])){
			$page=intval($_GET['p']);
		}
		else{
			$page=1;
		}
		return $page;
	}
	
	/**
	 *根据节点id获取节点名称
	 *@param $node_id 节点id
	 */
	function get_node($node_id){
		$name=_fetch("SELECT nName FROM fe_node WHERE id={$node_id}","array")[0]["nName"];
		return $name;
	}
	
	/**
	 *根据topic_id获取topic的title
	 *@param $topic_id 
	 */
	function get_title($topic_id){
		$query="SELECT title FROM fe_topic WHERE id={$topic_id}";
		$title=_fetch($query,'array')[0]['title'];
		return $title;
	}
	
	/**
	 * 根据topic_id获取topic的text
	 */
	function get_topic_text($topic_id){
		$query="SELECT text FROM fe_topic WHERE id={$topic_id}";
		$text=_fetch($query,'array')[0]['text'];
		return $text;
	}

	/**
	 *根据reply_id获取reply的内容
	 *@param $reply_id
	 */
	function get_reply_text($reply_id){
		$query="SELECT text FROM fe_reply WHERE id={$reply_id}";
		$text=_fetch($query,'array')[0]['text'];
		return $text;
	}
	 
	 
	/**
	 *根据topic_id获取创建者的user_id
	 *@param $topic_id
	 *@param $self $self=true的时候返回当前用户的user_id
	 */
	function get_user_id($topic_id=null,$self=null){
		if($self==true){
			return $_SESSION['user_index'];
		}
		if($topic_id){
			$query="SELECT uId FROM fe_topic WHERE id={$topic_id}";
			$user_id=_fetch($query,'array')[0]['uId'];
		}
		else if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$user_id=intval($_GET['u']);
		}
		else{
			return $_SESSION['user_index'];
		}
		return $user_id;
	}

	/**
	 *获取已发表的帖子数目
	 *@param $context="user"获取当前用户或其他用户已发表的话题数目，$context="node"获取某个节点下面的话题数目
	 *@param 对应节点的id
	 */
	 function get_topic_number($context="user",$node_id=null){
		if($context=="user"){
			if(isset($_GET['u'])&&is_numeric($_GET['u'])){
				$uid=intval($_GET['u']);
			}
			else{
				$uid=$_SESSION['user_index'];
			}
			$number=_fetch("SELECT id FROM fe_topic WHERE uId={$uid}","int");
		}
		else if($context=="node"){
			$number=_fetch("SELECT id FROM fe_topic WHERE nodeId={$node_id}","int");
		}
		return $number;
	 }
	
	
	/**
	 *获取当前用户或其他用户已发表的回复数目
	 */
	 function get_reply_number(){
		if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$uid=intval($_GET['u']);
		}
		else{
			$uid=$_SESSION['user_index'];
		}
		$number=_fetch("SELECT id FROM fe_reply WHERE uId={$uid}","int");
		return $number;
	 }
	 
	/**
	 *获取当前用户或者其他用户已收藏的帖子
	 *@param $type 要获取的信息的类型，'detail'代表已收藏帖子的详细信息，'number'代表已收藏帖子的数目,默认为'detail'
	 */
	function get_collection($type='detail'){
		if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$uid=intval($_GET['u']);
		}
		else{
			$uid=$_SESSION['user_index'];
		}
		if($type==='number'){
			$number=_fetch("SELECT * FROM fe_collect WHERE uId={$uid}","int");
			return $number;
		}
		else{
			$collect_array=array();
			$topic_id=_fetch("SELECT tId FROM fe_collect WHERE uId={$uid}","array");
			if(is_array($topic_id)){	//如果用户收藏了帖子
				foreach($topic_id as $tid){
					$topic_id=$tid['tId'];
					$query="SELECT user.username,user.id AS uid,topic.id,topic.cTime,topic.title,topic.text,topic.click_times,node.id AS nodeid,node.nName FROM fe_user AS user INNER JOIN fe_topic AS topic ON user.id=topic.uId INNER JOIN fe_node AS node ON topic.nodeId=node.id WHERE topic.id={$topic_id} ORDER BY topic.cTime;";
					$topic=extract_topic($query);
					$collect_array[]=$topic[0];
				}
				return $collect_array;
			}
			return $collect_array;
		}
	}
	
	/**
	 *获取所有的节点信息
	 */
	function get_all_node(){
		$query_node="select cate.cName,node.nName,node.id from fe_category as cate left join fe_node as node on cate.id=node.cId";
		$node_arr=_fetch($query_node,"array");
		$node=array();
		if(is_array($node_arr)){
			foreach($node_arr as $item){
				$keys=array_keys($node);
				$cName=$item['cName'];
				$nName=$item['nName'];
				$nId=$item['id'];
				$nodeItem=array('id'=>$nId,'name'=>$nName);
				if(!in_array($cName,$keys)){
					$node[$cName]=array();
				}
				if($nName!==null){
					array_push($node[$cName],$nodeItem);
				}
			}
		}
		return $node;
	}
	
	/**
	 *获取已创建的所有分类
	 */
	function get_categories(){
		$query_categories="SELECT id,cName FROM fe_category";
		$categories=array();
		$category_arr=_fetch($query_categories,"array");
		if(is_array($category_arr)){	//如果已经创建了分类
			$categories=$category_arr;
		}
		return $categories;
	}
	
	
	/*=====获取最热门的节点，以节点下的话题数量为以依据=====(待完成.....)*/
	function fe_hotNodes(){
		$getHotNode="SELECT node.nName,node.id,COUNT(node.id) AS counts FROM fe_node AS node INNER JOIN fe_topic AS topic ON topic.nodeId=node.id GROUP BY node.id ORDER BY counts DESC LIMIT 16";
		$hotNode=_fetch($getHotNode,'array');
		if(is_array($hotNode)&&count($hotNode)==16){
			return $hotNode;
		}
		else{
			$getHotNode="SELECT id,nName FROM fe_node LIMIT 16";
			$hotNode=_fetch($getHotNode,'array');
			return $hotNode;
		}
	}
	
	/*
	*提取用户头像的图片文件名
	*@param $owner_id 用户id
	*@param $type 图片类型
	*/
	function fe_userFace($owner_id=null,$type='small'){
		if($owner_id){
			$uid=$owner_id;
		}
		else if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$uid=intval($_GET['u']);
		}
		else{
			$uid=$_SESSION['user_index'];
		}
		if(_fetch("select * from fe_face where uId='{$uid}'","int")){
			$tmp_face=_fetch("select * from fe_face where uId='{$uid}'","array")[0]['fName'];
		}
		else{
			$tmp_face="default.png";
		}
		if($type=="big"){
			$tmp_face="big_".$tmp_face;
		}
		else if($type=="max"){
			$tmp_face="max_".$tmp_face;
		}
		$face=_path("home")."/fe-content/fe-face/".$tmp_face;
		return $face;
	}
	
	/**
	 *获取当前用户或者其他用户的用户名
	 *@param $user_id
	 */
	 function get_username($user_id=null){
		if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$uid=intval($_GET['u']);
			$username=_fetch("SELECT username FROM fe_user WHERE id={$uid}","array")[0]['username'];
		}
		else if(!$user_id){
			$username=$_SESSION['username'];
		}
		else{
			$query="SELECT username FROM fe_user WHERE id={$user_id}";
			$username=_fetch($query,'array')[0]['username'];
		}
		return $username;
	 }
	 
	/**
     *操作用户的荣誉值，可执行的操作分别为获取，增加，削减，荣誉值初始值为零，荣誉值大于零的用户每次发帖会削减5个荣誉值，有人回复帖子
	 *增加15个荣誉值，有人收藏或喜欢帖子增加10个荣誉值
	 */
	 
	/*
	 *获取用户的荣誉值
	 */
	function get_reputation(){
		if(isset($_GET['u'])&&is_numeric($_GET['u'])){
			$owner_id=intval($_GET['u']);
		}
		else{
			$owner_id=$_SESSION['user_index'];
		}
		$reputation=_fetch("SELECT reputation FROM fe_user WHERE id={$owner_id}","array")[0]['reputation'];
		return $reputation;
	}
	
	/**
	 *增加用户的荣誉值
	 *@param $topic_id 帖子在数据库中的id索引，根据它找到帖子的创建者的id
	 *@param $level    操作的等级，'high'代表增加15个荣誉值，'medium'代表增加10个荣誉值
	 */
	 function raise_reputation($topic_id,$level){
		if($level==='high'){
			$amount=15;
		}
		else if($level==='medium'){
			$amount=10;
		}
		$owner_id=_fetch("SELECT uId FROM fe_topic WHERE id={$topic_id}","array")[0]['uId'];
		$query="UPDATE fe_user SET reputation=reputation+{$amount} WHERE id={$owner_id}";
		_update($query);
	 }
	 
	/** 
	 *削减用户的荣誉值
	 */
	 function cut_reputation(){
		$owner_id=$_SESSION['user_index'];
		$current_reputation=get_reputation();
		if($current_reputation==0){
			return;
		}
		else{
			$amount=5;
			$query="UPDATE fe_user SET reputation=reputation-{$amount} WHERE id={$owner_id}";
			_update($query);
		}
	 }
	 
	/*
	*处理时间戳
	*@param $timestamp
	*/
	function format_time($timestamp){
		$format='Y-n-W-j-G-i';
		$now_time=explode('-',date($format));
		$topic_time=explode('-',date($format,$timestamp));
		$ago_str=array('年前','月前','周前','天前','小时前','分钟前','刚刚');
		for($i=0;$i<6;$i++){
			if($now_time[$i]>$topic_time[$i]){
				$period_str=($now_time[$i]-$topic_time[$i]).$ago_str[$i];
				return $period_str;
			}
			else{
				continue;
			}
		}
		$period_str=$ago_str[$i];
		return $period_str;
	}
	
	/*
	*分页函数
	*@$page 页码
	*此处还需要实现考虑已关注的人的动态显示，待完成。。。。。。。
	*/
	function pagination($node_id=null,$user_id=null,$page=1){
		$page=intval($page);
		$config=new config();
		$topic_page=$config->getConfig('topicPage');
		if($node_id!==null){
			$topic_amounts=_fetch("SELECT * FROM fe_topic WHERE nodeId={$node_id}","int");
			$tmp_str="WHERE topic.nodeId={$node_id}";
		}
		else if($user_id!==null){
			$topic_amounts=_fetch("SELECT * FROM fe_topic WHERE uId={$user_id}","int");
			$tmp_str="WHERE topic.uId={$user_id}";
		}
		else{
			if($page==1){	//如果是第一页，获取好友动态
				if(_checkLogin()){
					$dynamics=get_dynamics();
					if(is_array($dynamics)){	//如果有新的好友动态
						$dynamic_count=count($dynamics);
						$topic_amounts=_fetch("SELECT * FROM fe_topic","int")+$dynamic_count;
						$max_pagination=ceil($topic_amounts/$topic_page);
						$_SESSION['max_pagination']=$max_pagination;
						if($dynamic_count==$topic_page){
							$_SESSION['offset']=0;
							return $dynamics;
						}
						else{
							$_SESSION['offset']=$topic_page-$dynamic_count;
							$get_topic="SELECT user.username,user.id AS uid,topic.id,topic.cTime,topic.final_reply_time,topic.title,topic.text,topic.click_times,node.id AS nodeid,node.nName FROM fe_user AS user INNER JOIN fe_topic AS topic ON user.id=topic.uId INNER JOIN fe_node AS node ON topic.nodeId=node.id ORDER BY topic.final_reply_time DESC LIMIT {$_SESSION['offset']}";
							$topic_list=extract_topic($get_topic);
							$topic_list=array_merge($dynamics,$topic_list);
							$topic_array['max_pagination']=$max_pagination;
							$topic_array['current_page']=$page;
							$topic_array['list']=$topic_list;
							return $topic_array;
						}
					}
				}
				unset($_SESSION['offset']);
				$topic_amounts=_fetch("SELECT * FROM fe_topic","int");
				$max_pagination=ceil($topic_amounts/$topic_page);
				$_SESSION['max_pagination']=$max_pagination;
			}	
			if(isset($_SESSION['offset'])){ //存在好友动态
					$offset=($page-2)*$topic_page+$_SESSION['offset'];
			}
			else{
				$offset=($page-1)*$topic_page;
			}
			$get_topic="SELECT user.username,user.id AS uid,topic.id,topic.cTime,topic.final_reply_time,topic.title,topic.text,topic.click_times,node.id AS nodeid,node.nName FROM fe_user AS user INNER JOIN fe_topic AS topic ON user.id=topic.uId INNER JOIN fe_node AS node ON topic.nodeId=node.id ORDER BY topic.final_reply_time DESC LIMIT {$offset},{$topic_page}";
			$topic_list=extract_topic($get_topic);
			$topic_array['max_pagination']=$_SESSION['max_pagination'];
			$topic_array['current_page']=$page;
			$topic_array['list']=$topic_list;
			return $topic_array;
		}
		$max_pagination=ceil($topic_amounts/$topic_page);
		if($page>$max_pagination){
			$page=1;
		}
		$offset=($page-1)*$topic_page;
		$get_topic="SELECT user.username,user.id AS uid,topic.id,topic.cTime,topic.final_reply_time,topic.title,topic.text,topic.click_times,node.id 
		AS nodeid,node.nName FROM fe_user AS user INNER JOIN fe_topic AS topic ON user.id=topic.uId INNER JOIN fe_node AS node ON topic.nodeId=node.id ".$tmp_str." ORDER BY topic.final_reply_time DESC LIMIT {$offset},{$topic_page}";
		$topic_list=extract_topic($get_topic);
		$topic_array['current_page']=$page;
		$topic_array['max_pagination']=$max_pagination;
		$topic_array['list']=$topic_list;
		return $topic_array;
	}
	
	/*
	*提取话题的信息，包括作者，创建时间和所属节点等等
	*@param $extract_sql 用于查询的sql语句
	*/
	function extract_topic($extract_sql){
		$topic=_fetch($extract_sql,'array');
		$topic_array=array();
		if(is_array($topic)){
			foreach($topic as $item){
				$topic_id=$item['id'];
				$topic_item=array();
				$topic_item['topic_title']=$item['title'];
				$topic_item['topic_text']=$item['text'];
				$topic_item['topic_id']=$topic_id;
				$topic_item['created_time']=$item['cTime'];
				$topic_item['click_times']=$item['click_times'];
				$topic_item['node_name']=$item['nName'];
				$topic_item['node_id']=$item['nodeid'];
				$topic_item['author_name']=$item['username'];
				$topic_item['author_id']=$item['uid'];
				$reply_amounts=_fetch("SELECT id AS reply_amounts FROM fe_reply WHERE tId={$topic_id}","int");
				if($reply_amounts>0){
					$latest_reply=_fetch("SELECT uId,cTime FROM fe_reply WHERE tId={$topic_id} ORDER BY cTime DESC LIMIT 1","array")[0];
					$latest_reply_uid=$latest_reply['uId'];
					$latest_reply_time=$latest_reply['cTime'];
					$latest_replier=_fetch("SELECT username FROM fe_user WHERE id={$latest_reply_uid}","array")[0]['username'];
					$topic_item['reply_amounts']=$reply_amounts;
					$topic_item['latest_replier']=$latest_replier;
					$topic_item['latest_reply_uid']=$latest_reply_uid;
					$topic_item['latest_reply_time']=$latest_reply_time;
				}
				$topic_array[]=$topic_item;
			}
		}
		return $topic_array;
	}
	
	/*
	*提取当前请求的话题的所有信息
	*/
	function extract_topic_all($topic_id=null){
		$extract_sql="SELECT user.username,user.id AS uid,topic.id,topic.cTime,topic.title,topic.text,topic.click_times,node.id AS nodeid,node.nName FROM fe_user AS user INNER JOIN fe_topic AS topic ON user.id=topic.uId INNER JOIN fe_node AS node ON topic.nodeId=node.id WHERE topic.id=".($topic_id?$topic_id:$_SESSION['topic_id']);
		$topic=extract_topic($extract_sql)[0];
		if(isset($topic['reply_amounts'])&&$topic['reply_amounts']>0){
			/*
			*提取话题下面的所有回复信息并存入数组
			*/
			$reply_array=extract_reply($topic['topic_id']);
			$topic['replies']=$reply_array;
		}
		return $topic;
	}
	
	/*
	*提取当前请求的话题下面的所有回复内容
	*@param $topic_id
	*@param $page 页码，在用户资料页面对回复进行分页显示
	*/
	function extract_reply($topic_id,$user_id=null,$page=1){
		$config=new config();
		$topic_page=$config->getConfig('topicPage');
		if($topic_id!==null){
			$tmp_str="WHERE reply.tId={$topic_id} ORDER BY reply.cTime DESC";
		}
		else if($user_id!==null){
			$reply_amounts=_fetch("SELECT id FROM fe_reply WHERE uId={$user_id}","int");
			$max_pagination=ceil($reply_amounts/$topic_page);
			$offset=($page-1)*$topic_page;
			$tmp_str="WHERE reply.uId={$user_id} ORDER BY reply.cTime DESC LIMIT {$offset},{$topic_page}";
		}
		$get_reply="SELECT reply.id,reply.tId,reply.text,reply.cTime,reply.agree_times,user.id AS uid,user.username FROM fe_reply AS reply INNER JOIN fe_user AS user ON reply.uId=user.id ".$tmp_str;
		$reply=_fetch($get_reply,'array');
		$reply_array=array();
		$reply_list=array();
		foreach($reply as $item){
			$reply_item=array();
			$reply_item['reply_id']=$item['id'];
			$reply_item['reply_author_name']=$item['username'];
			$reply_item['reply_author_id']=$item['uid'];
			$reply_item['reply_text']=$item['text'];
			$reply_item['topic_id']=$item['tId'];
			$reply_item['reply_agree_times']=$item['agree_times'];
			$reply_item['reply_created_time']=$item['cTime'];
			$reply_list[]=$reply_item;
		}
		if(isset($max_pagination)){
			$reply_array['max_pagination']=$max_pagination;
			$reply_array['current_page']=$page;
			$reply_array['list']=$reply_list;
			return $reply_array;
		}
		else{
			return $reply_list;
		}
	}
	
	/*
	*给当前请求话题的点击次数加一次
	*@param $topic_id
	*/
	function click_times_add($topic_id){
		_update("UPDATE fe_topic SET click_times=click_times+1 WHERE id={$topic_id}");
		return ;
	}
	
	/*
	*判断当前请求的话题是否已经被收藏并生成对应的链接
	*@param $topic_id;
	*/
	function topic_collection($topic_id){
		if(!_checkLogin()){
			$link="<a href='fe-action.php?act=collect&t={$topic_id}' id='collect-topic'>加入收藏</a>";
			return $link;
		}
		$query_sql="SELECT * FROM fe_collect WHERE tId={$topic_id} AND uId={$_SESSION['user_index']}";
		$is_collected=_fetch($query_sql,'int');
		$link="<a href='fe-action.php?act=collect&t={$topic_id}' id='collect-topic'>".($is_collected?'取消收藏':'加入收藏')."</a>";
		return $link;
	}
	
	/*
	*判断当前请求的话题是否已经投过票并生成对应的链接
	*@param $topic_id
	*/
	function topic_vote($topic_id){
		if(!_checkLogin()){
			$link="<a href='fe-action.php?act=vote&t={$topic_id}' id='vote-topic'>喜欢</a>";
			return $link;
		}
		$query_sql="SELECT * FROM fe_vote WHERE topic_id={$topic_id} AND user_id={$_SESSION['user_index']}";
		$is_voted=_fetch($query_sql,'int');
		$link="<a href='fe-action.php?act=vote&t={$topic_id}' id='vote-topic'>".($is_voted?'感谢已表示':'喜欢')."</a>";
		return $link;
	}
	
	/*
	*判断回复信息是否已经被点赞并生成相应的链接信息
	*@param $reply_id
	*/
	function reply_agreement($reply_id){
		if(!_checkLogin()){
			$link="<a href='fe-action.php?act=agree&r={$reply_id}' class='agree-reply'>赞</a>";
			return $link;
		}
		$query_sql="SELECT * FROM fe_agreement WHERE reply_id={$reply_id} AND user_id={$_SESSION['user_index']}";
		$is_agree=_fetch($query_sql,'int');
		$link="<a href='fe-action.php?act=agree&r={$reply_id}' class='agree-reply ".($is_agree?'agree-already':'')."'>".($is_agree?'取消赞':'赞')."</a>";
		return $link;
	}
	
	/*
	*获取当前正在操作的节点的id和名称
	*/
	function current_node(){
		/*
		*$node_id由get传入，此处需要调用防sql注入模块对参数进行过滤
		*/
		$node_id=$_GET['n'];
		$query="SELECT nName FROM fe_node WHERE id={$node_id}";
		$name=_fetch($query,'array')[0]['nName'];
		$node=array();
		$node['node_id']=$node_id;
		$node['node_name']=$name;
		return $node;
	}
	
	/*
	*获取某一个用户的所有可用信息
	*@param $owner 用户类型，是当前账号用户还是其他用户
	*/
	function user_information($owner='present'){
		if($owner==='present'){
			$owner_id=$_SESSION['user_index'];
		}
		else if($owner==='other'){
			array_walk($_GET,'input_walk');
			if(preg_match('/\d+?/',$_GET['u'],$match)){
				$owner_id=intval($match[0]);
			}
		}
		$query="SELECT id,username,email,signature,city,blog,company,github,douban,weibo,introduction,regTime,reputation FROM fe_user WHERE id={$owner_id}";
		$information=_fetch($query,'array')[0];
		return $information;
	}
	
	/**
	 *检查是否存在*-error.json文件,存在则说明表单填写错误，返回json文件
	 */
	 function exist_error(){
		$file_path=dirname(dirname(__FILE__)).'/fe-content/json/'.$_SESSION['user_index'].'-error.json';
		if(file_exists($file_path)){
			$json=file_get_contents($file_path);
			return $json;
		}
		else{
			return false;
		}
	 }
	 
	/**
	 *查询是否已关注当前浏览的主页的所有者
	 */
	function is_concern(){
		$active_id=$_SESSION['user_index'];
		$passive_id=get_user_id();
		if(_fetch("SELECT id FROM fe_concern WHERE active_id={$active_id} AND passive_id={$passive_id}","int")>0){	//已经关注
			return '取消关注';
		}
		else{	//还未关注
			return '关注他';
		}
	}
	
	/**
	 *查询是否有未读提醒，包括别人的@和私信，返回json数据,前端通过Ajax获取
	 */
	function have_mention(){
		$user_id=$_SESSION['user_index'];
		$mention_number=_fetch("SELECT id FROM fe_mention WHERE to_uid={$user_id} AND have_read='not'","int");
		$letter_number=_fetch("SELECT id FROM fe_letter WHERE to_uid={$user_id} AND have_read='not'","int");
		$not_read=array('mention'=>$mention_number,'letter'=>$letter_number);
		$json=json_encode($not_read);
		header('Content-type:text/json');
		echo $json;
		exit;
	}
	
	/**
	 *获取被编辑的话题的原始信息
	 */
	function get_original_topic(){
		if($_GET['t']&&is_numeric($_GET['t'])){
			$topic_id=$_GET['t'];
			$owner_id=$_SESSION['user_index'];
			$query_owner="SELECT uId FROM fe_topic WHERE id={$topic_id}";
			$author_id=_fetch($query_owner,'array')[0]['uId'];
			if($author_id===$owner_id){	//如果这条话题确实是你创建你才有编辑的权限
				$original_topic=array();
				$query_original="SELECT title,text FROM fe_topic WHERE id={$topic_id}";
				$topic=_fetch($query_original,'array')[0];
				$original_topic['id']=$topic_id;
				$original_topic['title']=$topic['title'];
				$original_topic['text']=$topic['text'];
				return $original_topic;
			}
			else{	//企图修改别人的内容只会得到'http error';
				exit('http error');
			}
		}
		else{
			exit('http erroe');
		}
	}
	
	/**
	 *获取被编辑的回复的原始内容
	 */
	function get_original_reply(){
		if(isset($_GET['r'])&&is_numeric($_GET['r'])){
			$reply_id=$_GET['r'];
			$owner_id=$_SESSION['user_index'];
			$query_owner="SELECT uId FROM fe_reply WHERE id={$reply_id}";
			$author_id=_fetch($query_owner,'array')[0]['uId'];
			if($author_id===$owner_id){	//如果这条回复确实是你创建你才有编辑的权限
				$query_reply="SELECT tId,text FROM fe_reply WHERE id={$reply_id}";
				$reply=_fetch($query_reply,'array')[0];
				$original_reply=array();
				$original_reply['id']=$reply_id;
				$original_reply['topic_id']=$reply['tId'];
				$original_reply['text']=$reply['text'];
				return $original_reply;
			}
			else{	//企图修改别人的内容只会得到'http error';
				exit('http error');
			}
		}
		else{
			exit('http error');
		}
	}
	
	/**
	 *获取包含表单填写错误错误信息的json文件
	 */
	function get_error_file(){
		$user_id=$_SESSION['user_index'];
		$file=dirname(dirname(__FILE__)).'/fe-content/json/'.$user_id.'-error.json';
		if(file_exists($file)){	//文件存在
			$json=file_get_contents($file);
			unlink($file);	//文件内容被提取后立马删除
			$obj=json_decode($json);
			$html="<ul id='form-error'>";
			foreach($obj as $attr){
				$html.="<li>{$attr}</li>";
			}
			$html.="</ul>";
			return $html;
		}
		else{	//文件不存在
			return null;
		}
	}
	
?>