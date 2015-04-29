<?php 
	/**
	 *管理节点的页面
	 */
	define('CALL_TOKEN','f2ecreek');
	require_once("header.php");
	require_once("menubar.php");
?>
<div class="contentWrapper" id="contentWrapper">
	<h1 class="subject node-page"><i class="icon"></i>节点目录</h1>
	<div class="contentBox">
		<div class="addItem" id="addItemBox">
			<em class="sub-subject">添加新节点</em>
			<form action="#" method="POST">
				<input type="hidden" name="csrf_token" id="token" value="<?php echo _create_token();?>" />
				<div class="form-control">
					<i class="inputHint">名称</i>
					<input type="text" name="newNodeName" size="15" id="newNode" class="newItemName" />
					<i class="explanation">这将是它在站点上显示的名字</i>
				</div>
				<div class="form-control">
					<i class="inputHint">选择分类</i>
					<select class="cateList" name="belongCate" id="belongCate">
			<?php 
				$categories=get_categories();
				$number=count($categories);
				if($number>0):
					foreach($categories as $item):
						
			?>
						<option value="<?php echo $item['id'];?>" id="<?php echo $item['cName'];?>"><?php echo $item['cName'];?></option>
			<?php 
					endforeach;
				else:
			?>
						<option>没有创建分类</option>
			<?php 
				endif;
			?>
					</select>
				</div>
				<div class="form-control">
					<input type="submit" name="submit" class="submit" id="submit" value="添加" />
				</div>
			</form>
		</div>
		<div class="allItem">
			<em class="sub-subject">所有节点</em>
			<table class="contentTable" id="nodeTable">
				<tr>
					<th class="name">名称</th>
					<th class="category">所属分类</th>
					<th class="number">话题数目</th>
					<th class="edition">操作</th>
				</tr>
	<?php 
		$nodes=get_all_node();
		$number=count($nodes);
		if($number>0):
			foreach($nodes as $category=>$items):
				foreach($items as $item):
	?>
				<tr>
					<td class="name"><?php echo $item['name'];?></td>
					<td class="category"><?php echo $category;?></td>
					<td class="number"><?php echo get_topic_number("node",$item['id']);?></td>
					<td class="edition"><a href="javascript:" class="edit">编辑</a></td>
				</tr>
	<?php
				endforeach;
			endforeach;
		endif;
	?>
				<tr id="template">
					<td class="name"></td>
					<td class="category"></td>
					<td class="number"></td>
					<td class="edition"><a href="javascript:" class="edit">编辑</a></td>
				</tr>
			</table>
			<input type="text" class="newItemName" id="editing-node" />
			<input type="text" class="newItemName" id='editing-category' />
			<a href="javascript:" id="quit" class="quit">取消</a>
		</div>
	</div>
</div>
<?php 
	require_once("footer.php");
?>