<div class="footer" id="footer" style="width:1159px;border-top:1px solid #E1E1E1;height:50px;line-height:50px;float:left;color:#9B9B9B;font-size:12px;margin-left:20px;">
前端交流分享社区
</div>
<script type="text/javascript" src="js/header.js"></script>
<script type="text/javascript" src="js/menubar.js"></script>
<script typo="text/javascript" src="js/footer.js"></script>
<script type="text/javascript" id="script"></script>
<script type="text/javascript">
	var href=location.href;
	var components=href.split('/');
	var max_index=components.length-1;
	var current=components[max_index].split('.')[0];
	var script=$('script');
	if(current!=='admin'){
		script.setAttribute('src','js/'+current+'.js');
	}
</script>
</body>
</html>