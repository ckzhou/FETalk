<div class="content sidebar">
	<h4 class="subject working-status">运行状态</h4>
	<table class="detail-status">
		<tr>
			<th>注册成员</th>
			<td><?php echo fe_userNum();?></td>
		</tr>
		<tr>
			<th>节点</th>
			<td><?php echo fe_nodeNum();?></td>
		</tr>
		<tr>
			<th>主题</th>
			<td><?php echo fe_allTopicNum();?></td>
		</tr>
		<tr>
			<th>回复</th>
			<td><?php echo fe_allReplyNum();?></td>
		</tr>
	</table>
</div>