<?php 
	/**
	 *管理话题页面
	 */
	define("CALL_TOKEN","f2ecreek");
	require_once("header.php");
	require_once("menubar.php");
?>
<div class="contentWrapper" id='contentWrapper'>
	<h1 class="subject topic-page"><i class="icon"></i>话题目录</h1>
	<div class="contentBox">
		<div class="addItem search-topic">
			<em class="sub-subject">搜索帖子</em>
			<form action="#" method="POST">
				<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo _create_token();?>" />
				<div class="form-control">
					<i class="inputHint">帖子标题</i>
					<input type="text" name="newCategoryName" size="15" id="key-word" class="newItemName" />
				</div>
				<div class="form-control">
					<input type="submit" name="submit" class="submit" id="submit" value="搜索" />
				</div>
			</form>
		</div>
		<div class="allItem all-topic">
			<em class="sub-subject" id="table-hint">最近发布的帖子</em>
			<table class="contentTable" id="topicTable">
				<thead>
					<tr>
						<th class="title">标题</th>
						<th class="node">节点</th>
						<th class="author">创建者</th>
						<th class="time">发布时间</th>
						<th class="deletion">操作</th>
					</tr>
				</thead>
				<tbody>
		<?php 
			$topic=get_recent_topic();
			$number=count($topic);
			if($number>0):
				foreach($topic as $item):
		?>
					<tr>
						<td class="title" title="<?php echo $item['title'];?>"><?php echo $item['title'];?></td>
						<td class="node"><?php echo $item['node'];?></td>
						<td class="author"><?php echo $item['author'];?></td>
						<td class="time"><?php echo $item['time'];?></td>
						<td class="deletion"><a href="javascript:" class="delete">删除</a></td>
					</tr>
		<?php 
				endforeach;
			else:
		?>
				<tr id="no-topic">
						<td colspan="5">目前还没有帖子</td>
				</tr>	
		<?php 
			endif;
		?>
				</tbody>
				<tfoot>
					<tr id="template">
						<td class="title"></td>
						<td class="node"></td>
						<td class="author"></td>
						<td class="time"></td>
						<td class="deletion"><a href="javascript:" class="delete">删除</a></td>
					</tr>
					<tr id="no-result">
						<td colspan="5">没有相关帖子</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php 
	require_once("footer.php");
?>