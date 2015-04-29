/**
 *控制侧边栏使其和右边内容区域高度一致
 */
function controlMenubar(){
	var menubar=$('menu-bar');
	var wrapper=$('contentWrapper');
	var footer=$('footer');
	var height=wrapper.offsetHeight+footer.offsetHeight;	//右边内容区域高度
	var height_document=document.documentElement.clientHeight||document.body.clientHeight;	//文档占据区域的总高度
	if(height<height_document){
		var diff_value=height_document-height;	
		footer.style.marginTop=diff_value+'px';
		menubar.style.height=(height_document-30)+'px';
	}
	else{
		footer.style.marginTop=0;
		menubar.style.height=(height-30)+'px';
	}
}
controlMenubar();