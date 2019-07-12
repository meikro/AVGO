function aqiyiSolid() {
    let window_width = $(window).width();
    let active_width = window_width * 0.93335;
    let no_active_width = window_width * 0.88;
    let offset = (window_width - active_width) / 2;

    let active_option = offset;
    let active_next_option = active_width + offset;
    let active_befor_option = -no_active_width + offset;
    let other_option = no_active_width + active_width + offset;
    let active_css = {
        transform: 'translate(' + active_option + 'px, 0px) translateZ(0px)',
        transitionDuration: '0ms',
        zIndex: 3,
        transition: 'all 0.5s ease-in-out'
    };
    let next_css = {
        transform: 'translate(' + active_next_option + 'px, 0px) translateZ(0px)',
        transitionDuration: '0ms',
        zIndex: 2,
        transition: 'all 0.5s ease-in-out'
    };

    let before_css = {
        transform: 'translate(' + active_befor_option + 'px, 0px) translateZ(0px)',
        transitionDuration: '0ms',
        zIndex: 2,
        transition: 'all 0.5s ease-in-out'
    };
    let other_css = {
        transform: 'translate(' + other_option + 'px, 0px) translateZ(0px)',
        transitionDuration: '400ms',
        zIndex: 1,
        transition: 'all 0.5s ease-in-out'
    };
    let index = 0;
    let length = $("#solid2 li").size();
    let interval;
    let startX = 0;
    let moveEndX = 0;

    $("#solid2 li").each(function () {
        if ($(this).index() == 0) {
            $(this).addClass("active").css(active_css);
        } else if ($(this).index() == 1) {
            $(this).removeClass("active").css(next_css);
        } else if ($(this).index() == length - 1) {
            $(this).removeClass("active").css(before_css);
        } else {
            $(this).removeClass("active").css(other_css);
        }
    });

    $("#solid2").bind("touchstart", function (e) {
        clearInterval(interval);

        e.preventDefault();
        startX = e.originalEvent.changedTouches[0].pageX;
    })

    $("#solid2").bind("touchmove", function (e) {
        e.preventDefault();
        moveEndX = e.originalEvent.changedTouches[0].pageX;
       
    })

    $("#solid2").bind("touchend", function (e) {
        let X = moveEndX - startX;

        if (X > 0) {
            index--;
            if(index <0){
                index = length-1;
            }
        } else if (X < 0) {
            index++;
            if(index == length){
                index = 0;
            }
        }
        setAnimate(index, length, active_css, next_css, before_css, other_css);
        startX = 0;
        moveEndX = 0;
        interval = setInterval(function () {
            index++;
            if (index == length) {
                index = 0;
            }
            setAnimate(index, length, active_css, next_css, before_css, other_css);
        }, 3000);
    })


    interval = setInterval(function () {
        index++;
        if (index == length) {
            index = 0;
        }
        setAnimate(index, length, active_css, next_css, before_css, other_css);
    }, 3000)

    $(window).resize(function () {
        clearInterval(interval);
    })

}

function setAnimate(index, length, active_css, next_css, before_css, other_css) {
   

    if(index == length){
        index = 0;
        aqiyiSolid.index = 0;
    }

    if(index <0 ){
        index = length-1;
        aqiyiSolid.index = length-1;
    }

    if (index == 0) {
        $("#solid2 li").each(function () {
            if ($(this).index() == 0) {
                $(this).addClass("active").css(active_css);
            } else if ($(this).index() == 1) {
                $(this).removeClass("active").css(next_css);
            } else if ($(this).index() == length - 1) {
                $(this).removeClass("active").css(before_css);
            } else {
                $(this).removeClass("active").css(other_css);
            }
        })
    } else if (index == length - 1) {
        $("#solid2 li").each(function () {
            if ($(this).index() == length - 1) {
                $(this).addClass("active").css(active_css);
            } else if ($(this).index() == 0) {
                $(this).removeClass("active").css(next_css);
            } else if ($(this).index() == length - 2) {
                $(this).removeClass("active").css(before_css);
            } else {
                $(this).removeClass("active").css(other_css);
            }
        })
    } else {
        $("#solid2 li").each(function () {
            let _this = this;
            if ($(this).index() == index) {
                $(this).addClass("active").css(active_css);
            } else if ($(this).index() == index + 1) {
                $(this).removeClass("active").css(next_css);
            } else if ($(this).index() == index - 1) {
                $(this).removeClass("active").css(before_css);
            } else {
                $(this).removeClass("active").css(other_css);
            }
        })
    }
}