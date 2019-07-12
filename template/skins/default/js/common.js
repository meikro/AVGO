var wap = navigator.userAgent.match(/iPad|iPhone|Android|Linux|iPod/i) != null;
$(function () {
    //手机端隐藏分页
    if(wap){
        var pi = parseInt($('.fy-box li.am-active a').html());
        console.log(pi);
        $('.fy-box>li').each(function(){
            var p = parseInt($(this).find('a').html());
            if(!isNaN(p)){
                if(pi > 99){
                    if(!(p > pi-2 && p < pi+2)) $(this).hide();
                }else{
                    if(!(p > pi-3 && p < pi+3)) $(this).hide();  
                }
            }
        });
    }
    if($('.middle-box').width() <= 1232) {
        if ($('.tab1232.active').length>0) {
            let l_w = $('.tab1232.active').offset().left - ($('.middle-box').width() / 2);
            $("#center_xs_nav").scrollLeft(l_w);
        }
    }
    $(".container").css("padding-top", $(".web-nav-box").height());
    $(".hover-icon").hover(function () {
        $(this).children('.icon').addClass('active');
    }, function () {
        $(this).children('.icon').removeClass('active');
    });
    $(window).resize(function(){
        var right = $(document).width()-$('.middle-box').offset().left-$('.middle-box').width();
        $('.control-img-box').css('right',right);
        //$(".container").css("padding-top", $(".web-nav-box").height())
    });
    var flag = 0;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 250) {
            if(flag == 0){
                $(".web-nav-box").css("position", "fixed").hide().stop().fadeIn(500);
                flag = 1;
            }
            $(".container").css("padding-top", 140)
        } else {
            flag = 0;
            $(".web-nav-box").css("position", "relative");
            $(".container").css("padding-top", 0)
        }
    });
    //二维码
    $('nav .webma').attr('src','https://www.baifubao.com/o2o/0/qrcode?size=10&text='+encodeURIComponent(window.location.host));
    $(".fix-button").hover(function () {
        $(this).children(".center").children(".s24x26").addClass("active");
    }, function () {
        $(this).children(".center").children(".s24x26").removeClass("active");
    });
    $(".xieyi").click(function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active").children(".icon").removeClass("active");
        } else {
            $(this).addClass("active").children(".icon").addClass("active");
        }
    });
    $('body').click(function(e) {
        if($('#phone_nav').length > 0){
            $('#phone_nav').hide();
        }
        if($(e.target).closest(".nav-search-box").length==0){
            $('.onblur-box').hide();
        }
        if($(e.target).closest(".pf-box").length==0){
            $('.pf-data-box').hide();
            $(".pf-box .show-msg-icon").removeClass('active');
        }
    });
    //评分显示
    $('.start-box').hover(function() {
        $('.pfxia').addClass("active");
        $("#pf").show();
    }, function() {
        $('.pfxia').removeClass("active");
        $("#pf").hide();
    });
	$('.login-already').click(function() {
		stopProp(event);
		if($(this).attr('data-sid') == '1'){
			$('#phone_nav').hide();
			$(this).attr('data-sid','0');
		}else{
			$(this).attr('data-sid','1');
			$('#phone_nav').show();
			$('#phone_nav .nav-user-box').show().siblings().hide();
		}
	});
	$('.wapnav').click(function() {
		stopProp(event);
		if($(this).attr('data-sid') == '1'){
			$('#phone_nav').hide();
			$(this).attr('data-sid','0');
		}else{
			$(this).attr('data-sid','1');
			$('#phone_nav').show();
			$('#phone_nav .nav-class-box').show().siblings().hide();
		}
	});
    //历史搜索
    if($('#history-search').length > 0) get_history('search');
    //搜索
	$('.show1232-search').click(function() {
		stopProp(event);
		if($(this).attr('data-sid') == '1'){
			$('#phone_nav').hide();
			$(this).attr('data-sid','0');
		}else{
			$(this).attr('data-sid','1');
			$('#phone_nav').show();
			$('#phone_nav .nav-search-box').show().siblings().hide();
		}
	});
    $('#search-btn').click(function() {
        var key = $('#search_key').val();
        if(key == ''){
            ty_tip('请输入搜索关键字!!!',2);
            $("#search_key").focus();
        }else{
            set_history(key,'search');
            window.location.href = links('search','index','',key);
        }
    });
    $('#search-btn-wap').click(function() {
        var key = $('#search-key-wap').val();
        if(key == ''){
            ty_tip('请输入搜索关键字!!!',2);
            $("#search-key-wap").focus();
        }else{
            set_history(key,'search');
            window.location.href = links('search','index','',key);
        }
    });
    //清楚历史搜索记录
    $('.clear-hostory').click(function() {
        del_cookie('history_search');
        get_history('search');
    });
    //观看记录
    if($('#history-list').length > 0) get_history('history');
    //登录
    $('.ctcms_login').click(function() {
        get_log();
    });
    //打赏
    $('.ctcms_shang_btn').click(function() {
        is_log();
        var gift_id = $('#gift_id').val();
        var gift_num = parseInt($('.ctcms_gift_nums').val());
        var ucion = parseInt($('#ucion').html());
        var zcion = parseInt($('#g_monet').html());
        if(gift_id == 0){
            ty_tip('请选择礼物!!!',2);
        }else if(ucion < zcion){
            ty_tip('金币不足，请充值!!!',2);
        }else{
            $.post(links('ajax','dsto'), {
                vid:vod_id,num:gift_num,did:gift_id
            }, function(data){
                if(data.code==2){
                    is_log();
                }
                if(data.code==1){
                    ty_tip(data.msg,2);
                }
                if(data.code==0){
                    ty_tip('恭喜您，打赏已送到！',1);
                    $('#ucion').html(ucion-zcion);
                    $('.ds-pop').hide();
                    $('.mask').hide();
                    $('body').removeClass('body-fixed');
                }
            },"json"); 
        }
    });
    //点赞
    $('.vod_zan').click(function() {
        is_log();
        $.post(links('ajax','zan'), {
            did:vod_id
        }, function(data){
            if(data.code == 1){
                ty_tip(data.msg,2);
            }else{
                ty_tip(data.msg,1);
                var dhits = parseInt($('#dhits').html())+1;
                $('#dhits').html(dhits);
            }
            iconAddActiveClass('.vod_zan');
        },"json"); 
    });
    //收藏
    $('.vod_fav').click(function() {
        is_log();
        $.post(links('ajax','fav'), {
            did:vod_id
        }, function(data){
            if(data.code == 1){
                ty_tip(data.msg,2);
            }else{
                ty_tip(data.msg,1);
                var dhits = parseInt($('#dhits').html())+1;
                $('#dhits').html(dhits);
            }
            iconAddActiveClass('.vod_fav');
        },"json"); 
    });
    //报错
    $('.vod_err').click(function() {
        var err = $(this).attr('data-err');
        if(err == ''){
            ty_tip('请选择报错类型!!!',2);
        }else{
            $.post(links('ajax','err'), {
                did:vod_id,zu:vod_zid,ji:vod_jid,err:err
            }, function(data){
                if(data.code == 1){
                    ty_tip(data.msg,2);
                }else{
                    ty_tip(data.msg,1);
                    $('.common-cz-pop').hide();
                    $('.mask').hide();
                    $('body').removeClass('body-fixed');
                }
            },"json"); 
        }
    });
    //判断登录
    if($('.ctcms_log').length > 0){
        $.get(links('user','ajax','ulog'),function (str) {
            $('.ctcms_log').html(rechange(str));
        });
    }
})
function rechange(str){
    return str.replace(/document.writeln\("/g,"").replace(/"\);/g,"").replace(/\\\"/g,"\"").replace(/\\\'/g,"\'").replace(/\\\//g,"\/").replace(/\\\\/g,"\\");
}
//判断登录
function is_log() {
    if(document.cookie.indexOf('ctcms_uid=') < 0) {
        $('.mask').show();$('#loginPop').show();
        return;
    }
}
//登录
function get_log() {
    var name = $('#username').val();
    var pass = $('#userpass').val();
    if(name == ''){
        ty_tip('请输入登录账号!!!',2);
    } else if(pass == ''){
        ty_tip('请输入登录密码!!!',2);
    } else {
        $.post(links('user','ajax','login'), {name: name,pass: pass}, function(data ){
            if(data == 'ok'){
                ty_tip('登录成功',1);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                ty_tip(data,2);
            }
        });
    }
}
//读取记录
function get_history(k) {
    if(k == 'search'){
        var history_list = get_cookie('history_search');
        if(history_list){
            var arr = new Array();
            arr = history_list.split("#ctcms#");
            var html = '';
            for (var i = 0; i < arr.length; i++) {
                html+='<li><a href="'+links('search','index','',arr[i])+'">'+arr[i]+'</a></li>';
            }
            $('#history-search').html(html);
        }else{
            $('.search-hide').hide();
        }
    }else{
        var history_list = get_cookie('history_list');
        if(history_list){
            var arr = new Array();
            arr = history_list.split("#ctcms#");
            var html = '';
            for (var i = 0; i < arr.length; i++) {
                var arr2 = new Array();
                arr2 = arr[i].split("|");
                html+='<li class="data"><i class="icon nav-history-icon"></i><a href="'+arr2[0]+'" class="name">'+arr2[1]+'</a><span class="type">'+arr2[2]+'</span></li>';
            }
            $('#history-list').html(html);
        }
    }
}
//写入记录
function set_history(_name,_link,_cname) {
    if(_link == 'search'){
        var historys = _name;
        var history_list = get_cookie('history_search');
        if(history_list){
            var arr = new Array();
            arr = history_list.split("#ctcms#");
            var len = arr.length > 4 ? 4 : arr.length;
            for (var i = 0; i < len; i++) {
                if(_name != arr[i]){
                    historys += '#ctcms#'+arr[i];
                }
            }
        }
        set_cookie('history_search',historys);
    }else{
        var historys = _link+'|'+_name+'|'+_cname;
        var history_list = get_cookie('history_list');
        if(history_list){
            var arr = new Array();
            arr = history_list.split("#ctcms#");
            var len = arr.length > 9 ? 9 : arr.length;
            for (var i = 0; i < len; i++) {
                var arr2 = new Array();
                arr2 = arr[i].split("|");
                if(_name != arr2[1] && _cname != arr2[2]){
                    historys += '#ctcms#'+arr[i];
                }
            }
        }
        set_cookie('history_list',historys);
    }
}
//读cookie
function get_cookie(name) {
    var arr,reg = new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)){
        return unescape(arr[2]);
    }else{
        return null;
    }
}
//存cookie
function set_cookie(name,value){
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString() + ";path=/";
}
//删除cookie
function del_cookie(name){
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval = get_cookie(name);
    if(cval != null){
        document.cookie= name + "="+cval+";expires="+exp.toGMTString() + ";path=/";
    }
}
function iconAddActiveClass(t) {
    if ($(t).hasClass('active')) {
        $(t).removeClass('active').children('.icon').removeClass('active');
        if ($(t).hasClass('showText')) {
            $("#vodText").stop().hide(200);
        }
    } else {
        $(t).addClass('active').children('.icon').addClass('active');
        if ($(t).hasClass('showText')) {
            $("#vodText").stop().show(200);
        }
    }
    if ($(t).hasClass('sc')) {
        if ($(t).hasClass('isActive')) {
            $(t).removeClass('isActive').children('.icon').removeClass('isActive');
        } else {
            $(t).addClass('isActive').children('.icon').addClass('isActive');
        }
    }
}
function iconAddClassSiblings(t) {
    $(t).addClass('active').children('.icon').addClass('active');
    $(t).siblings().removeClass('active').children('.icon').removeClass('active');
}

function addActive(t) {
    $(t).addClass("active").siblings().removeClass("active");
}

function tab(t) {
    $(t).addClass("active").siblings().removeClass("active");
    var page = $(t).attr("data-page");
    $("#" + page).show().siblings().hide();
}

// 播放线路选择
function vod_bf_line_solid() {
    var line_index = 0;
    var line_width;
    var line_size = $(".bf-resour-list .data").size();
    var line_box_w = $(".bf-resour-list-box").width();
    if ($(".middle-box").width() == 1232) {
        line_width = 96;
    } else {
        line_width = 78;
    }
    $(".bf-resour-list").width(line_width * line_size);
    $(window).resize(function () {
        if ($(".middle-box").width() == 1232) {
            line_width = 96;
        } else {
            line_width = 78;
        }
        $(".bf-resour-list").css("left", 0);
        $(".bf-resour-list").width(line_width * line_size);
    })
    $("#line_r").click(function () {
        line_index++;
        if ($(".middle-box").width() == 1232) {
            if (line_index > line_size - 3) {
                line_index = 0;
            }

        } else {
            if (line_index > line_size - 5) {
                line_index = 0;
            }
        }
        $(".bf-resour-list").css("left", -line_index * line_width);
    })
    $("#line_l").click(function () {
        line_index--;
        if (line_index < 0) {
            if ($(".middle-box").width() == 1232) {
                line_index = line_size - 3;
            } else {
                line_index = line_size - 5;
            }
        }

        $(".bf-resour-list").css("left", -line_index * line_width);
    })
}
// 播放窗口放大缩小
function vod_bf_setting_size() {
    $(".show-big-vod-icon").click(function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $("#scroll").show();
            if ($(".middle-box").width() == 1730) {
                $("#bf_box").css("width", 1274);
                $("#bf_box").children(".bfq").css("height", 718)
            } else if ($(".middle-box").width() == 1480) {
                $("#bf_box").css("width", 1022);
                $("#bf_box").children(".bfq").css("height", 576)
            } else if ($(".middle-box").width() == 1232) {
                $("#bf_box").css("width", 882);
                $("#bf_box").children(".bfq").css("height", 497)
            }
        } else {
            $(this).addClass("active");
            $("#scroll").hide();
            $("#bf_box").css("width", "100%");
            if ($(".middle-box").width() == 1730) {
                $("#bf_box").children(".bfq").css("height", 975)
            } else if ($(".middle-box").width() == 1480) {
                $("#bf_box").children(".bfq").css("height", 834)
            } else if ($(".middle-box").width() == 1232) {
                $("#bf_box").children(".bfq").css("height", 693)
            }
        }

        $(window).resize(function () {
            if ($(".show-big-vod-icon").hasClass("active")) {
                $("#bf_box").css("width", "100%");
                if ($(".middle-box").width() == 1730) {
                    $("#bf_box").children(".bfq").css("height", 975)
                } else if ($(".middle-box").width() == 1480) {
                    $("#bf_box").children(".bfq").css("height", 834)
                } else if ($(".middle-box").width() == 1232) {
                    $("#bf_box").children(".bfq").css("height", 693)
                }
            } else {
                if ($(".middle-box").width() == 1730) {
                    $("#bf_box").css("width", 1274);
                    $("#bf_box").children(".bfq").css("height", 718)
                } else if ($(".middle-box").width() == 1480) {
                    $("#bf_box").css("width", 1022);
                    $("#bf_box").children(".bfq").css("height", 576)
                } else if ($(".middle-box").width() == 1232) {
                    $("#bf_box").css("width", 882);
                    $("#bf_box").children(".bfq").css("height", 497)
                }

            }
        })
    });
}
// 阻止事件冒泡
function stopProp(event) {
    event.stopPropagation();
}
// 通用提示框
function common_tip(msg, time) {
    let str;
    if (time) {
        str = ' <div class="common-cz-pop fixed-center common-tip-pop" id="commentTip">' +
            '<div class="pop-msg">' + msg + '</div></div>';
    } else {
        str = ' <div class="common-cz-pop fixed-center common-tip-pop" id="commentTip">' +
            '<i class="icon pop-cloce-icon" onclick="$(\'#commentTip\').remove();$(\'.mask\').hide()"></i><div class="pop-msg">' + msg + '</div></div>';
    }
    $("body").append(str);
    if (time) {
        setTimeout(function () {
            $("#commentTip").remove();
        }, time)
    }
}

// 评星级
function star(s) {
    //_id:触发函数效果的元素选择器
    var controller = true;
    let _id = s.getId;
    _id.hover(function () {
        if (controller) {
            $(this).addClass('active').prevAll().addClass('active');
        }
    }, function () {
        if (!controller) {
            return;
        } else {
            $(this).removeClass('active').siblings().removeClass('active');
        }
    });
    _id.click(function () {
        if (controller) {
            controller = false;
        } else {
            controller = true;
        }
        var pf = $(this).attr('data-pf');
        $.post(links('ajax','pf'), {did: vod_id,pf: pf}, function(r) {
            if(r.code == 1){
                ty_tip(r.msg,2);
            } else {
                $('#pf_fen').html(r.pf);
                $('#pf_num').html(r.pfnum);
                $('#pf_bi1 .myd-percent-icon').css('width',r.pf5_bi+'%');
                $('#pf_bi1 .nums').html(r.pf5_bi+'%');
                $('#pf_bi2 .myd-percent-icon').css('width',r.pf4_bi+'%');
                $('#pf_bi2 .nums').html(r.pf4_bi+'%');
                $('#pf_bi3 .myd-percent-icon').css('width',r.pf3_bi+'%');
                $('#pf_bi3 .nums').html(r.pf3_bi+'%');
                $('#pf_bi4 .myd-percent-icon').css('width',r.pf3_bi+'%');
                $('#pf_bi4 .nums').html(r.pf2_bi+'%');
                $('#pf_bi5 .myd-percent-icon').css('width',r.pf2_bi+'%');
                $('#pf_bi5 .nums').html(r.pf1_bi+'%');
                ty_tip(r.msg,1);
            }
        },'json');
    });
}
// 通用删除提示框
function showDelPop(msg,ac,op,id) {
    if (!msg) {
        msg = '删除后不可恢复，是否确定删除？';
    }
    var str = '<div class="delete-tip-pop fixed-center">' +
        '<div class="tip-box absolute-center">' +
        '<div class="tip-msg">' + msg + '</div>' +
        '<div class="btn-group">' +
        '<button class="qx" onclick="$(\'.delete-tip-pop\').remove()">取消</button>' +
        '<button class="cz" onclick="get_del(\''+links(ac,op,id)+'\','+id+')">确定</button></div></div></div>';
    $('body').append(str);
}
function get_del(link,ids){ 
    $.get(link, function(data){
         if(data.msg == 'ok' || data.code == 0){
            $('#reply'+ids).remove();
         }else{
            ty_tip(data['msg'],2);
         }
    },'json');
    $('.delete-tip-pop').remove();
}
/*
 单图上传
 s:{
uploadfileID:input[type="file"]的文件id,
sawfile_target:显示图片的div选择器,
uploadBtn:提交按钮选择器,
employeeImageUploadUrl:图片上传接口地址,
PicUrl_target:储存图片服务器地址的input[type='hidden']选择器
}
*/
function openPicFile(s) {
    var num = 0;
    $("#" + s.uploadfileID).change(function () {
        var size = this.files[0].size / 1024;
        var name = this.value;
        var fileName = name.substring(name.lastIndexOf(".") + 1).toLowerCase();
        if (fileName != "png" && fileName != "jpg" && fileName != "gif") {
            ty_tip("请上传图片格式文件！",2);
            $("#" + s.uploadfileID).val("");
            if (isIE() == "IE") {
                ieCleaFile(this);
            }
        } else if (size > 300) {
            ty_tip("图片大小不能超过300KB",2);
            $("#" + s.uploadfileID).val("");
            if (isIE() == "IE") {
                ieCleaFile(this);
            }
        } else {
            num = 1;
            $(s.sawfile_target).css('backgroundImage', 'url(' + window.URL.createObjectURL(this.files[0]) + ')'); //图片路径
        }
        if (s.uploadBtn) {
            $(s.uploadBtn).click(function () {
                if (num == 0) {
                    ty_tip("请上传图片！",2);
                }
                if (num == 1 && s.employeeImageUploadUrl) {
                    ajaxFileUpload(s.employeeImageUploadUrl, s.uploadfileID, s.PicUrl_target);
                }
                num = 0
            });
        } else {
            if (num == 1 && s.employeeImageUploadUrl) {
                ajaxFileUpload(s.employeeImageUploadUrl, s.uploadfileID, s.PicUrl_target);
            }
            num = 0
        }
    });
    function ieCleaFile(file) {
        file.after(file.clone());
        file.remove();
    }
}

//employeeImageUploadUrl:图片上传地址
//uploadfileID:input[type="file"]的文件id
//PicUrl_target:储存图片服务器地址的input[type='hidden']选择器
function ajaxFileUpload(employeeImageUploadUrl, uploadfileID, PicUrl_target) {
    console.log(employeeImageUploadUrl);
    $.ajaxFileUpload({
        type: "post",
        url: employeeImageUploadUrl,
        secureuri: false,
        fileElementId: uploadfileID,
        dataType: 'json',
        success: function (data, status) {
            if (data.code == 1) {
                if(PicUrl_target) $(PicUrl_target).val(data.url);
                ty_tip("上传成功！",1);
            } else {
                ty_tip("上传失败！",2);
            }
        },
        error: function (data, status, e) {
            ty_tip("上传失败！",2);
        }
    })
}


// 通用单图轮播
function common_solid(s) {
    let img_tag = s.img; //图片选择器
    let left_btn = s.l_btn; //左控制按钮
    let right_btn = s.r_btn; //右控制按钮
    let bottom_btn = s.b_btn; //底部控制按钮
    let index = 0;
    let index_length = $(s.img).size();
    setSolidIndex(index, img_tag, bottom_btn);
    $(left_btn).click(function () {
        index--;
        if (index < 0) {
            index = index_length - 1;
        }
        setSolidIndex(index, img_tag, bottom_btn);
    })
    $(right_btn).click(function () {
        index++;
        if (index == index_length) {
            index = 0;
        }
        setSolidIndex(index, img_tag, bottom_btn);
    })
}

function setSolidIndex(index, img_tag, bottom_btn) {
    $(img_tag).eq(index).show().siblings(img_tag).hide();
    $(bottom_btn).eq(index).addClass('active').siblings().removeClass("active");
}

// 通用提示
function ty_tip(msg, type) {
    let str;
    if (type == 1) {
        str = '<div class="common-tip fixed-center" id="c_tip"><i class="icon right-circel-icon left"></i>' + msg + '</div>';
    } else {
        str = '<div class="common-tip fixed-center" id="c_tip"><i class="icon wrong-circel-icon left"></i>' + msg + '</div>';
    }
    $('body').append(str);
    setTimeout(function () {
        $("#c_tip").remove();
    }, 2000)
}

//页面链接
function links(ac,op,id,wd) {
    var link = ctcms_path+'index.php';
    if(optlink.search('=') > -1){
        if(ac == 'user'){
            link+='?d='+ac;
            if(op) link+='&c='+op;
            if(id) link+='&m='+id;
        }else{
            link+='?c='+ac;
            if(op) link+='&m='+op;
            if(id) link+='&id='+id;
        }
        if(wd){
			if(ac=='search'){
				link+='&wd='+encodeURIComponent(wd);
			}else{
				link+='&'+wd;
			}
		}
    }else{
        link+='/'+ac;
        if(op) link+='/'+op;
        if(id) link+='/'+id;
        if(wd){
			if(ac=='search'){
				link+='&wd='+encodeURIComponent(wd);
			}else{
				link+='?'+wd;
			}
		}
    }
    return link;
}

// 左侧导航选中状态
function left_nav(){
    $("#center_nav .tab").each(function(){
            if($(this).attr("page-name") == page_name){
                $(this).addClass("active").children('a').children(".s23x22").addClass("active");
            }else{
                $(this).removeClass("active").children('a').children(".s23x22").removeClass("active");
            }
        })
   }