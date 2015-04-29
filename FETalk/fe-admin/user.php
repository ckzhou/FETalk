<?php 
	/**
	 *管理网站用户的页面
	 */
	define("CALL_TOKEN","f2ecreek");
	require_once("header.php");
	require_once("menubar.php");
?>
<div class="contentWrapper" id='contentWrapper'>
	<h1 class="subject user-page"><i class="icon"></i>用户列表</h1>
	<div class="contentBox">
		<div class="addItem search-user">
			<em class="sub-subject">搜索用户</em>
			<form action="#" method="POST">
				<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo _create_token();?>" />
				<div class="form-control">
					<i class="inputHint">用户名</i>
					<input type="text" name="newCategoryName" size="15" id="key-word" class="newItemName" />
				</div>
				<div class="form-control">
					<input type="submit" name="submit" class="submit" id="submit" value="搜索" />
				</div>
			</form>
		</div>
		<div class="allItem all-user">
			<em class="sub-subject" id="table-hint">最近注册的用户</em>
			<table class="contentTable" id="userTable">
				<thead>
					<tr>
						<th class="name">用户名</th>
						<th class="email">Email</th>
						<th class="time">注册时间</th>
						<th class="number">发帖数量</th>
						<th class="gag">操作</th>
					</tr>
				</thead>
				<tbody>
		<?php 
			$user=get_recent_user();
			$number=count($user);
			if($number>0):
				foreach($user as $item):
		?>
					<tr>
						<td class="name"><?php echo $item['name'];?></td>
						<td class="email"><?php echo $item['email'];?></td>
						<td class="time"><?php echo $item['time'];?></td>
						<td class="number"><?php echo $item['number'];?></td>
				<?php 
					if(checkDefriend($item['email'])):	//已经被拉黑
				?>
						<td class="gag"><a href="javascript:" class="relive">解除</a></td>
				<?php 
					else:	//还没有被拉黑
				?>
						<td class="gag"><a href="javascript:" class="defriend">关闭</a></td>
				<?php 
					endif;
				?>
					</tr>
		<?php 
				endforeach;
			endif;
		?>
				</tbody>
				<tfoot>
					<tr id="template">
						<td class="name"></td>
						<td class="email"></td>
						<td class="time"></td>
						<td class="number"></td>
						<td class="gag"><a href="javascript:" class="close">关闭</a></td>
					</tr>
					<tr id="no-result">
						<td colspan="5">没有相关用户</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php 
	require_once("footer.php");
?>