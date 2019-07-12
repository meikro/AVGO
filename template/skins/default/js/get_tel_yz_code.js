var tel_yz_time = 60;
var tel_yz_inteval;

$(function(){
    $("#texYZM").bind("click",sendCode);
})

function sendCode(){
    $("#texYZM").unbind("click");
    getYzTime();
    tel_yz_inteval = setInterval(function(){
        getYzTime();
    },1000)
    
}

function getYzTime(){
    tel_yz_time--;
    if(tel_yz_time == 0){
        $("#texYZM").html("发送验证码").bind("click",sendCode);
        tel_yz_time = 60;
        clearInterval( tel_yz_inteval);
    }else{
        $("#texYZM").html(tel_yz_time+"S后重发");
    }
}