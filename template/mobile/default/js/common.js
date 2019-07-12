$(document).ready(function(){
	$(".main_visual").hover(function(){
		//$("#btn_prev,#btn_next").fadeIn()
	},function(){
		$("#btn_prev,#btn_next").fadeOut()
	});
	
	$dragBln = false;
	
	$(".main_image").touchSlider({
		flexible : true,
		speed : 200,
		btn_prev : $("#btn_prev"),
		btn_next : $("#btn_next"),
		paging : $(".flicking_con a"),
		counter : function (e){
			$(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
		}
	});
	
	$(".main_image").bind("mousedown", function() {
		$dragBln = false;
	});
	
	$(".main_image").bind("dragstart", function() {
		$dragBln = true;
	});
	
	$(".main_image a").click(function(){
		if($dragBln) {
			return false;
		}
	});
	
	timer = setInterval(function(){
		$("#btn_next").click();
	}, 2500);
	
	$(".main_visual").hover(function(){
		clearInterval(timer);
	},function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		},2500);
	});
	
	$(".main_image").bind("touchstart",function(){
		clearInterval(timer);
	}).bind("touchend", function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		}, 2500);
	});
	$(".seac").focus(function(){if(this.value=="请输入影片名进行搜索"){this.value='';}});
	$(".seac").blur(function(){if(this.value==""){this.value='请输入影片名进行搜索';}});
	//$("a").focus(function(){this.blur();});
	/*$(".header ul li a").click(function(){
		$(".header ul li a").attr("class","");
		$(this).attr("class","ons");
	});*/
	$(".view-font #mode b").click(function(){
		$(".view-font #mode b").attr("class","");
		$(this).attr("class","selc");
	});
	$(".view-font #mode #xl").click(function(){
		$(".view-font #B").slideDown();
		$(".view-font #A").slideUp();
	});
	$(".view-font #mode #jj").click(function(){
		$(".view-font #A").slideDown();
		$(".view-font #B").slideUp();
	});

	$(document).keyup(function (evnet) {
		if (evnet.keyCode == '13') {
			seach();
		}
	});
	settypeApi();
});
function seach(link)
{
	if($(".seac").val()=="" || $(".seac").val()=="请输入影片名进行搜索"){
		alert('请输入关键字，在搜索~');
	}else{
		if(link.indexOf("?")!=-1){
		    location.href=link+'&wd='+$(".seac").val();
		}else{
		    location.href=link+'?wd='+$(".seac").val();
		}
	}
}
function settypeApi(){
	$(".opentag").click(function(){
	    if($(".header-tag").css("height")=="70px"){
			$(".header-tag").css("height","auto");
			$(".opentag #btn").css("background-position","0px -60px");
			$(".opentag #btn").text("收起选项 ▲");
		}else{
			$(".header-tag").css("height","70px");
			$(".opentag #btn").css("background-position","0px -90px");
			$(".opentag #btn").text("展开选项 ▼");
		}
	});
}
var selhd=2;
function set_hd(hd){
	selhd=hd;
	$(".play-lists a").css({color:"#CCCCCC"});
	$(".play-lists a").eq(hd-1).css({color:"#FF9900"});
	$("#player").attr("src",playbody[pid].url+"&hd="+hd);
	location.hash='';
	location.hash='#head';
}