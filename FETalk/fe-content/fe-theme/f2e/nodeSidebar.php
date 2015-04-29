<div class="content sidebar">
	<h4 class="subject hot-nodes">最热节点</h4>
	<div class="nodes">
<?php 
$hotNode=fe_hotNodes();
if(is_array($hotNode)):
	foreach($hotNode as $node):
?>
		<a href="fe-node.php?n=<?php echo $node['id'];?>" class="hotNode"><?php echo $node['nName'];?></a>
<?php 
	endforeach;
endif;
?>
	</div>
</div>