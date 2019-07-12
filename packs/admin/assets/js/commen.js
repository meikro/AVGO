//index-in页面高度控制
var screenHeight = $(window).height();//浏览器高度
var screenWidth = $(window).width();//浏览器宽度
var left_nav_height = screenHeight-110;//宽度>768内容高度
var bodyheight = 0;
var upindex = null;
$(function(){
    if($('.edit').length > 0){
        ctcmsedit.init();
    }
    layer.config({
        extend: 'extend/layer.ext.js',
        cancel: function(index, layero){ 
            $('body').css('height',bodyheight);
        }
    });
    /*全选*/
    $("#checkbox,#checkbox2").on("click" , function(){
        var sid=$(this).attr('sid');
        if(sid==1){
            $("table").find("input:checkbox").prop("checked",true);
            $(this).attr('sid','2');
        }else{
            $("table").find("input:checkbox").prop("checked",false);
            $(this).attr('sid','1');
        }
    });
    /*全选*/
    $("table thead th input:checkbox").on("click" , function(){
        $(this).closest("table").find("tr > td:first-child input:checkbox").prop("checked",$("table thead th input:checkbox").prop("checked"));
    });
});
var ctcmsedit = {
    'init' : function() {
        parent.bodyheight = parent.$('body').height();
        parent.$('body').css('height',$('.content-page').height()+80);
        parent.$('.layui-layer').css('height',$('.content-page').height()+80);
    }
};
/*弹出层*/
/*
    参数解释：
    title   标题
    url     请求的url
    id      需要操作的数据id
    w       弹出层宽度（缺省调默认值）
    h       弹出层高度（缺省调默认值）
*/
function layer_show(title,url,w,h){
    if (title == null || title == '') {
        title=false;
    };
    if (w == null || w == '') {
        w = 800;
    };
    if (h == null || h == '') {
        h = ($(window).height() - 50);
    };
    var wap = navigator.userAgent.match(/iPad|iPhone|Android|Linux|iPod/i)!=null;
    if(wap){
        w = '100%';
        h = '100%';
    }
    w+='';h+='';
    if(w.search('%') == -1) w+='px';
    if(h.search('%') == -1) h+='px';
    upindex = layer.open({
        type: 2,
        area: [w, h],
        fix: false, //不固定
        maxmin: true,
        shade:0.4,
        title: title,
        content: url
    });
}
/*关闭弹出框口*/
function layer_close(){
    var index = parent.layer.getFrameIndex(window.name);
    parent.layer.close(index);
}
function get_msg(msg,time,bsid){
    bsid = (bsid == "" || bsid == undefined || bsid == null) ? 2 : bsid;
    times = (time == "" || time == undefined || time == null) ? 2000 : time;
    layer.msg(msg,{icon: bsid,time: times});
}
function indexHeight() {

    if(screenWidth>768){
        $(".left_nav").css('height',left_nav_height).addClass("active");
        $("#indexContent").css({"margin-left":200,"height":left_nav_height,"width":screenWidth-200});
    }else {
        $(".left_nav").css('height',"auto").removeClass("active");
        $("#indexContent").css({"margin-left":0,"height":left_nav_height,"width":screenWidth});
    }

    $(window).resize(function () {//当浏览器大小变化时

        if($(window).width()>768){
            $(".left_nav").css('height',$(window).height()-110).addClass("active");
            $(".solid-list").css('left',0);
            $("#indexContent").css({"margin-left":200,"height":$(window).height()-110,"width":$(window).width()-200})
        }else {
            $(".left_nav").css('height',"auto").removeClass("active");
            $("#indexContent").css({"margin-left":0,"height":$(window).height(),"width":$(window).width()})
        }

    });
}
// index-in页面跳转标题栏控制
function indexNav(){
    $(".first_li").click(function () {
        if($(window).width() <= 768){
            $(".left_nav").removeClass("active");
        }else{

            $(".left_nav").addClass("active");
        }

        var target = $(this).attr("tarName");
        $(this).addClass("active").siblings().removeClass("active");
        $(".menu").each(function () {
            if($(this).attr("tarName") == target){
                $(this).show().siblings().hide();
                $(".menu[tarName='"+target+"']").children(".first_menu").each(function (index,element) {
                    if(index == 0){
                        $(this).addClass("active");
                        $(this).children(".icon").addClass("active");
                    }else {
                        $(this).removeClass("active");
                        $(this).children(".icon").removeClass("active");
                    }
                });
                if(target == "main_page"){
                    $(".index-menu").each(function () {
                        $(this).removeClass("active")
                    })
                }
            }
        })
    });
    $(".index-menu").click(function () {

        if($(window).width() <= 768){
            $(".left_nav").removeClass("active");
        }else{

            $(".left_nav").addClass("active");
        }
        var target = $(this).attr("tarName");
        $(".menu").each(function () {
            if($(this).attr("tarName") == target){
                $(this).show().siblings().hide();
            }
        });
        $(".first_li").each(function () {
            if($(this).attr("tarName") == target){
                $(this).addClass("active").siblings().removeClass("active");
            }
        })
    });
    $(".first_menu").click(function () {
        if($(window).width() <= 768){
            $(".left_nav").removeClass("active");
        }else{
            $(".left_nav").addClass("active");
        }
        $(this).addClass("active").siblings().removeClass("active").children(".icon").removeClass("active");
        $(this).children(".icon").addClass("active").siblings().removeClass("active");
    });

    //导航栏切换
    var turnLeft = 0;
    $("#solidBtn").click(function () {
        var num = $(".first_li").size()+1;
        if(turnLeft <= -130*num+$(window).width() ){
            turnLeft = 0;
        }else {
            turnLeft = turnLeft - 130;
        }
        $(".solid-list").css("left",turnLeft);
    })

    $("#showLeftList").click(function(){
        if($(".left_nav").hasClass("active")){
            $(".left_nav").removeClass("active");
        }else {
            $(".left_nav").addClass("active");
        }

    })
}

/*
标签切换
 */
function tab(t) {
    $(t).addClass("active").siblings().removeClass("active");
    var page = $(t).attr("title");
    $("#"+page).show().siblings().hide();
}
