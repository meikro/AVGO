if (typeof jQuery == "undefined") {
    jQueryJS = document.createElement("script");
    jQueryJS.type = "text/javascript";
    jQueryJS.src = "//cdn.bootcss.com/jquery/1.11.2/jquery.min.js";
    document.getElementsByTagName("head")[0].appendChild(jQueryJS);
};
var uname,pl_page=1,pl_pagejs=1;
function get_pl(page){
	pl_page = page;
	$.post(ctcms_pllink, {did:pl_did,page: page}, function(data) {
		if(data['html']){
			pl_pagejs = data['pagejs'];
			$('#ctcms_pl').html(data['html']);
		}
	},'json');
}

function get_pl_up(){
	var page = pl_page-1;
	if(page < 1){
		alert('已经是第一页了');
	}else{
		get_pl(page);
	}
}
function get_pl_next(){
	var page = pl_page+1;
	if(pl_pagejs < page){
		alert('已经是最后一页了');
	}else{
		get_pl(page);
	}
}
function get_pl_reply(fid,name){
	uname = name;
	$("#fid").val(fid);
	$("#reply-to-box").text("");
	$("#reply-to-box").append(name);
	$('#replyclose').show();
	get_pl_scroll('replycontent');
	$('#replycontent').focus();
}
function get_pl_add() {
	var fid = $("#fid").val();
	var content = $("#replycontent").val();
	if(content==''){
		$("#rpnotice").html("数据不完整！");
		return;
	}
	$.post(ctcms_pladdlink, {
		did:pl_did,fid:fid,text:content
	}, function(data) {
		if(data['msg'] == 'ok'){
			$("#hfnum").html(parseInt($("#hfnum").html())+1);
			$("#rpnotice").html("回复成功");
			$("#replycontent").val("");
			setTimeout(function(){get_pl(1);},500);
		}else{
			$("#rpnotice").html(data['msg']);
			$("#replycontent").val('');
		}
	},'json');
}
function get_pl_del(link,ids){
	if(confirm("确定删除？")) { 
	    $.post(link, {id: ids}, function(data){
             if(data['msg']=='ok'){
				  $("#hfnum").html(parseInt($("#hfnum").html())-1);
                  $('#reply'+ids).remove();
			 }else{
                  alert(data['msg']);
			 }
	    },'json');
	}
}
function get_pl_close() {
	$("#replycontent").val('');
	$("#rpnotice").html('');
    $("#replyclose").hide();
    $("#fid").val('0');
    $("#reply-to-box").text('回复：楼主');
}
//滚动至指定位置
function get_pl_scroll(_id) {
     var scroll_offset = $("#"+_id+"").offset();  //得到pos这个div层的offset，包含两个值，top和left  
     $("body,html").animate({
           scrollTop:scroll_offset.top  //让body的scrollTop等于pos的top，就实现了滚动   
     },0);
}