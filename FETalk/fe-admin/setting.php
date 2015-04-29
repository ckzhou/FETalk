<?php 
	/**
	 *网站基本设置页面
	 */
	define("CALL_TOKEN","f2ecreek");
	require_once("header.php");
	require_once("menubar.php");
	$admin=$_SESSION['admin'];
	$config=new config;	
	$current_theme=$config->getConfig("theme");	//当前使用的主题
?>
<div class="contentWrapper" id='contentWrapper'>
	<h1 class="subject user-page"><i class="icon"></i>设置</h1>
	<div class="contentBox" id="contentBox">
		<div class="addItem setting-profile">
			<em class="sub-subject">编辑个人资料</em>
			<form action="#" method="POST">
				<input type="hidden" name="csrf_token" id="token" value="<?php echo _create_token();?>" />
				<input type="hidden" name="admin-id" id="admin-id" value="<?php echo $_SESSION["adminId"];?>" />
				<div class="form-control">
					<i class="inputHint">用户名</i>
					<input type="text" name="new-username" size="15" id="new-username" class="newItemName" value="<?php echo $admin;?>" />
				</div>
				<div class="form-control">
					<i class="inputHint">密码</i>
					<input type="password" name="new-password" size="15" id="new-password" class="newItemName" placeholder='密码不要少于6个字符'/>
				</div>
				<div class="form-control">
					<input type="submit" name="submit" class="submit" id="submit-profile" value="确定" />
				</div>
			</form>
		</div>
		<div class="addItem setting-skin">
			<em class="sub-subject">更改主题</em>
			<form action="#" method="POST">
				<div class="form-control">
					<i class="inputHint">当前主题：<strong id="current-theme"><?php echo $current_theme;?></strong></i>
				</div>
				<div class="form-control">
					<i class="inputHint">可用主题</strong></i>
					<select class="themeList" id="theme">
			<?php 
				$theme=get_all_theme();
				$number=count($theme);
				if($number>0):
					foreach($theme as $item):
			?>
						<option value="<?php echo $item;?>"><?php echo $item;?></option>
			<?php 
					endforeach;
				endif;
			?>
					</select>
				</div>
				<div class="form-control">
					<input type="submit" name="submit" class="submit" id="submit-theme" value="确定" />
				</div>
			</form>
		</div>
	</div>
	<div class="addItem setting-page">
		<em class="sub-subject">设置页面</em>
		<i class="inputHint">当前存在的页面</i>
		<table class="contentTable" id="pageTable">
			<thead>
				<tr>
					<th class="name">名称</th>
					<th class="template">模板</th>
					<th class="operation">操作</th>
				</tr>
			</thead>
			<tbody>
<?php 
	$page=get_all_page();
	$number=count($page);
	if($number>0):
		foreach($page as $item):
?>
				<tr>
					<td class="name"><?php echo $item['name'];?></td>
					<td class="template"><?php echo $item['template'];?></td>
					<td class="operation"><a href="javascript:" class="edit">编辑</a><a href="javascript:" class="delete">删除</a></td>
				</tr>
<?php 
		endforeach;
	else:
?>	
				<tr id="no-result">
						<td colspan="5">目前还没有创建页面</td>
				</tr>
<?php 
	endif;
?>
			</tbody>
			<tfoot>
				<tr id="tr-template">
					<td class="name"></td>
					<td class="template"></td>
					<td class="operation"><a href="javascript:" class="edit">编辑</a><a href="javascript:" class="delete">删除</a></td>
				</tr>
			</tfoot>
		</table>
		<input type="text" class="newItemName" id="editing-page" />
		<input type="text" class="newItemName" id="editing-template"/>
		<form action="#" method="POST">
			<div class="form-control">
				<i class="inputHint">添加页面</i>
				<input type="text" name="new-page" size="15" id="new-page" class="newItemName" />
				<i class="inputHint">设置模板</i>
				<select id="page-template" class="templateList">
		<?php 
			$template=get_page_template();
			$number=count($template);
			if($number>0):
				foreach($template as $item):
		?>
					<option value="<?php echo $item;?>"><?php echo $item;?></option>
		<?php 
				endforeach;
			else:
		?>
					<option value="">没有模板</option>
		<?php
			endif;
		?>
				</select>
			</div>
			<div class="form-control">
				<input type="submit" name="submit" class="submit" id="submit-page" value="确定" />
			</div>
		</form>
	</div>
</div>
<?php 
	require_once("footer.php");
?>