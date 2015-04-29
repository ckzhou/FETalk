<?php 
	/**
	 *利用139的smtp服务发送邮件
	 *@param $recipient 收信邮箱
	 *@param $subject 邮件标题
	 *@param $message 邮件内容
	 */
	class sendmail{
		/**
		 *构造函数，绑定基本信息
		 */
		function __construct($recipient,$subject,$message){
			$this->smtp="smtp.139.com";
			$this->sender="fetalk";
			$this->password=base64_encode("zhoucreek1993");
			$this->recipient=$recipient;
			$this->subject=$subject;
			$this->message=$message;
			$this->command=array("HELO localhost\r\n","AUTH LOGIN\r\n",base64_encode($this->sender)."\r\n","{$this->password}\r\n","MAIL FROM: <{$this->sender}@139.com>\r\n","RCPT TO: <{$this->recipient}>\r\n","DATA\r\n","From:FETalk@139.com\r\nTo: {$this->recipient}\r\nSubject: {$this->subject}\r\n\r\n{$this->message}","\r\n.\r\n");
		}
		
		/**
		 *创建socket并且连接SMTP服务器
		 */
		protected function createSocket(){
			$ip=gethostbyname($this->smtp);
			$socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
			$link=socket_connect($socket,$ip,25);
			if($link){
				$this->socket=$socket;
			}
		}
		
		/**
		 *发送邮件
		 */
		public function send(){
			$this->createSocket();
			$commands=$this->command;
			foreach($commands as $item){
				socket_write($this->socket,$item,strlen($item));
				echo socket_read($this->socket,1024);
			}
		}
	}
?>