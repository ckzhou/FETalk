<?php 
	/**
	 *管理分类的页面
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once('header.php');
	require_once('menubar.php');
?>
<div class="contentWrapper" id='contentWrapper'>
	<h1 class="subject category-page"><i class="icon"></i>分类目录</h1>
	<div class="contentBox">
		<div class="addItem">
			<em class="sub-subject">添加分类</em>
			<form action="#" method="POST">
				<input type="hidden" name="csrf_token" id="token" value="<?php echo _create_token();?>" />
				<div class="form-control">
					<i class="inputHint">名称</i>
					<input type="text" name="newCategoryName" size="15" id="newCategory" class="newItemName" />
					<i class="explanation">这将是它在站点上显示的名字</i>
				</div>
				<div class="form-control">
					<input type="submit" name="submit" class="submit" id="submit" value="添加" />
				</div>
			</form>
		</div>
		<div class="allItem">
			<em class="sub-subject">所有分类</em>
			<table class="contentTable" id="categoryTable">
				<tr>
					<th class="name">名称</th>
					<th class="number">节点数目</th>
					<th class="edition">操作</th>
				</tr>
	<?php 
		$categories=get_all_node();	//获取所有的节点信息
		$number=count($categories);
		if($number>0):	//如果管理员已经创建分类
			foreach($categories as $category=>$nodes):
	?>
				<tr>
					<td class="name"><?php echo $category;?></td>
					<td class="number"><?php echo count($nodes);?></td>
					<td class="edition"><a href="javascript:" class="edit">编辑</a></td>
				</tr>
	<?php
			endforeach;
		endif;
	?>
				<tr id="tr-template">
					<td class="name"></td>
					<td class="number"></td>
					<td class="edition"><a href="javascript:" class="edit">编辑</a></td>
				</tr>
			</table>
				<input type="text" class="newItemName" id="editing"/>
				<a href="javascript:" class="quit" id="quit">取消</a>
		</div>
	</div>
</div>
<?php 
	require_once("footer.php");
?>