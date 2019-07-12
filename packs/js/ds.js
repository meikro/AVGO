var sign = 0,dsid = 0,giftid = 0,giftcion = 0,layer = null;
var wap = navigator.userAgent.match(/iPad|iPhone|Android|Linux|iPod/i) != null;
layui.use(['jquery', 'layer'], function(){ 
  	$ = layui.$;
	layer = layui.layer;
	$('.ctcms_ds').click(function() {
		dsid = $(this).attr('data-id');
		if(!wap){
	    	var layerw = '565px';
	    	var layerh = '350px';
		}else{
	    	var layerw='90%';
	    	var layerh="350px";
		}
		lyindex = layer.open({
			type: 2,
			area: [layerw, layerh],
			fix: false, //不固定
			maxmin: true,
			shade:0.4,
			title: '打赏礼物',
			content: ctcms_dslink
		});
	});
	$('.ctcms_ds_xuan').click(function() {
	    sign = 1;
		giftid = $(this).attr('data-id');
		giftcion = $(this).attr('data-cion');
		$('.ctcms_gift_img').attr('src', $(this).attr('data-pic'));
		$('.ctcms_gift_sel').html($(this).attr('data-name'));
		$('.ctcms_gift_nums').val(1);
		$('.ctcms_gift_cion').html(giftcion);
	});
	$('.ctcms_shang_btn').click(function() {
		if(document.cookie.indexOf('ctcms_log=') < 0) {
	        layer.msg('登录超时，请先登录!',{icon:2});
	        return;
		}
		var nums = $('.ctcms_gift_nums').val();
	    if(sign ==0){
	        layer.msg('请选择礼物!',{icon:2});
	        return;
	    }
		$.post(parent.ctcms_dstolink, {
			vid:parent.dsid,num:nums,did:giftid
		}, function(data){
			if(data.code==2){
	            parent.window.location.href = data.url;
	        }
	        if(data.code==1){
	        	layer.msg(data.msg,{icon:2});
	        }
	        if(data.code==0){
	        	layer.msg('恭喜您，打赏成功！',{icon:1});
	        	if(parent.$('.ctcms_ds_list').length > 0){
					$.post(parent.ctcms_dslistlink,{
						id:parent.dsid
					}, function(data) {
						parent.$('.ctcms_ds_list').html(data);
					});
	        	}
	        	setTimeout(function() {
                    parent.layer.closeAll();
                }, 1000);
	        }
		},"json");
	});
	//加减数量
	$('.ctcms_gift_nums').change(function() {
		var nums = $(this).val();
		if(sign == 0){
			layer.msg('请选择礼物！');
			$(this).val(1);
		}
		if(nums < 1){
			nums = 1;
			$(this).val(nums);
		}
		$('.ctcms_gift_cion').html(nums*giftcion);
	});
	//加载打赏列表
	if($('.ctcms_ds_list').length > 0){
		dsid = $('.ctcms_ds_list').attr('data-id');
		$.post(ctcms_dslistlink,{
			id:dsid
		}, function(data) {
			parent.$('.ctcms_ds_list').html(data);
		});
	}
});