<?php 
	/**
	 *管理后台首页
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once('header.php');
	require_once('menubar.php');
?>
<div class="contentWrapper" id='contentWrapper'>
	<article class="markdown-body">
		<h3><a id="user-content-fetalk" class="anchor" href="#fetalk" aria-hidden="true"><span class="octicon octicon-link"></span></a>FETalk</h3>

		<p>
			<strong>FETalk是一个前端分享交流社区</strong>,这是我学习web开发的第一个练手项目，网站的主要功能及前端界面均临摹自开源的前端分享社区<a href="http://www.f2e.im/">f2e.im</a>,感谢<a href="http://www.f2e.im/">f2e.im</a>提供的素材供学习,原版的<a href="http://www.f2e.im/">f2e.im</a>采用Python编写，代码托管在<a href="">Github.</a></p>

		<p>FETalk采用PHP编写，我在原版的基础上增加了关注某人，发送私信，回复点赞的功能，后端使用PHP+MySQL,前端使用原生JavaScript和CSS2,某些地反复参杂了一些CSS3属性，用的最多的即使border-radius属性，但是IE8不支持此属性，所以兼容性还有待进一步加强，整个网站除了后端的XSS过滤模块和MarkDown解析模块取自Github之外，其余均为独立编写.</p>

		<p>编写FETalk收获很大，掌握了PHP和MySQL的基本使用，也加强了对JavaScript的掌握程度，顺带在开发过程中也懂得了排查代码错误的基本方式以及解决问题的基本方法，即拆分法，把一个问题拆分为各个子模块，弄清楚数据的流向，明确各个模块的任务，逐一解决，再利用各模块预留的接口进行衔接，当然，最重要的是让我认识到了学习编程，动手写才是正道，学习一样东西最好的方法就是去使用它.</p>
	</article>
</div>
<?php 
	require_once('footer.php');
?>
