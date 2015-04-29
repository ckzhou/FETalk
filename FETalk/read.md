###FETalk文档

####数据库设置
#####网站数据库默认名称:fetalk,可根据需求去setup.php和/fe-config/config.inc.php文件中进行修改
#####数据表
+ fe_admin:存储管理员身份信息
	+ fe_agreement	---存储帖子回复点赞信息
	+ fe_category	---存储节点分类信息
	+ fe_collect	---存储被收藏的帖子信息
	+ fe_concern	---存储用户之间的关注
	+ fe_dynamics   ---存储关注的人的动态
	+ fe_face       ---存储用户的头像
	+ fe_letter     ---存储私信
	+ fe_mention   	---存储@提醒
	+ fe_menu   	---存储网站页面
	+ fe_node    	---存储帖子节点
	+ fe_node    	---存储帖子回复
	+ fe_topic   	---存储帖子信息
	+ fe_user    	---存储注册用户信息
	+ fe_vote    	---存储对帖子的赞同信息

####文档结构
+ fe-admin	---后台管理文件夹
	+ css	---css文件夹
	+ js	---js文件夹
	+ admin.php	---后台管理首页
	+ category.php	---管理帖子节点的分类
	+ footer.php	---页脚
	+ header.php	---页头
	+ logic.php		---逻辑操作
	+ login.php		---后台登陆
	+ menubar.php	---后台管理菜单
	+ node.php		---管理帖子节点
	+ setting.php	---后台基本设置
	+ topic.php		---管理帖子
	+ user.php		---管理注册用户
+ fe-config	---存放配置文件
	+ config.inc.php	---配置mysql基本信息及其他
	+ config.xml		---配置当前主题以及前端显示
+ fe-content ---内容文件夹
	+ fe-face	---存放用户头像
	+ fe-theme	---存放网站主题
	+ json		---存放因表单填写错误而生成的json文件
+ fe-lib	---存放库文件的文件夹
	+ config.class.php	---操作config.xml的类
	+ fe-api	---开发网站主题要用到的函数集合
	+ functions.php	---系统开发用到的函数集合
	+ mail.class.php	---邮件发送类
	+ mention.clsss.php ---实现@功能的类
	+ mysql.inc.php	---mysql的操作函数
	+ Parsedwon.php	---markdown解析类，取自github
	+ xssHtml.class.php	---xss过滤类，取自github
+ fe-action.php	---逻辑操作文件
+ fe-create.php ---发表帖子
+ fe-edit.php	---编辑已发表的帖子或回复
+ fe-forgot.php	---忘记密码
+ fe-include.php ---包含文件
+ fe-login.php	---用户登录
+ fe-register.php	---用户注册
+ fe-mention.php	---查看未读提醒
+ fe-node.php	---查看某个节点
+ fe-setting.php	---用户资料设置
+ fe-topic.php	---查看帖子
+ fe-user.php	---用户主页
+ index.php	---网站首页
+ fe-page.php	---菜单页面
+ setup.php	---系统安装文件