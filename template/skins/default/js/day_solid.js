var week_day = new Date().getDay();
var day_index = 0;
var day_size = 0;

$(function () {
    $("#wd"+week_day).show().siblings().hide();

    $(".week-day").each(function () {
        if ($(this).attr("data-week") == week_day) {
            $(this).addClass("active").siblings().removeClass("active");
            let _this = this;
            if($('.middle-box').width()<1232){
                let left = $(_this).offset().left-10;


                $(".week-tab-group-box").scrollLeft(left);
            }
        }
    })

    $(".todayList").each(function () {
        if ($(this).attr("data-week") == week_day) {
            $(this).show().addClass("active").siblings(".todayList").hide().removeClass(
                "active");
        }

        var list_width = $(this).children(".data").size();

        $(this).css("width", list_width * 250 - 18);
    })

    $(".week-day").click(function () {
        day_index = 1;
        var _day = $(this).attr("data-week");
        $("#wd"+_day).show().siblings().hide()
        $(this).addClass("active").siblings().removeClass("active");

        $(".todayList").each(function () {
            if ($(this).attr("data-week") == _day) {
                $(this).show().addClass("active").siblings(".todayList")
                    .hide().removeClass("active");
            }
        })
    })

    $("#dayL").click(function () {
        var _width = $(".todayList.active").width();
        day_size = $(".todayList.active").children(".data").size();

        day_index--;
        getIndex(day_index);
    })

    $("#dayR").click(function () {
        var _width = $(".todayList.active").width();
        day_size = $(".todayList.active").children(".data").size();

        day_index++;
        getIndex(day_index);

    })

})

function getIndex(index) {
    day_size = $(".todayList.active").children(".data").size();

    if (index < 0) {

        if ($(".middle-box").width() == 1232) {
            day_index = day_size - 5;
        }else if($(".middle-box").width() == 1480){
            day_index = day_size - 6;
        }else{
            day_index = day_size - 7;
        }
    }else{
        if ($(".middle-box").width() == 1232) {
            if (index > day_size-5) {
                day_index = 0;
            } else {
                day_index = index;
            }
        }else if($(".middle-box").width() == 1480){
            if (index > day_size-6) {
                day_index = 0;
            } else {
                day_index = index;
            }
        }else{
            if (index > day_size-7) {
                day_index = 0;
            } else {
                day_index = index;
            }
        }
    }

    $(".todayList.active").css("left", -day_index * 250);
}