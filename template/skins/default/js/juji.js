$(function(){
    control();
})

$(window).resize(function(){
    control();
})

function control(){
    $(".episode-list").each(function(){

        if($(".middle-box").width() == 1730){
            if($(this).children(".data").size()>36){
                $(this).addClass("active");
            }else{
                $(this).removeClass("active");
            }
        }else if($(".middle-box").width() == 1480){
            if($(this).children(".data").size()>27){
                $(this).addClass("active");
            }else{
                $(this).removeClass("active");
            }
        }else{
            if($(this).children(".data").size()>21){
                $(this).addClass("active");
            }else{
                $(this).removeClass("active");
            }
        }

    })
}