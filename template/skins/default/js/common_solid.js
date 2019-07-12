function common_solid(s){
    let index = 0;
    let size = $(s.solid_tag).size();
    let interval;

    interval = setInterval(function(){
        index ++;
        if(index == size){
            index = 0;
        }
       setSolidActive(index,s.solid_tag,s.solid_botton_btn);
    },3000);

    $(s.solid_botton_btn).hover(function(){
        setSolidActive($(this).index(),s.solid_tag,s.solid_botton_btn);
    });

    $(s.box_tag).hover(function(){

             clearInterval(interval);

        },function(){

            interval = setInterval(function(){
            index ++;
            if(index == size){
                index = 0;
            }
            setSolidActive(index,s.solid_tag,s.solid_botton_btn);
            
        },3000);
    });

    $(s.r_btn).click(function(){
        index ++;
        if(index == size){
            index = 0;
        }

       setSolidActive(index,s.solid_tag,s.solid_botton_btn);
    })

     $(s.l_btn).click(function(){
        index --;
        if(index < 0){
            index = size -1;
        }

        setSolidActive(index,s.solid_tag,s.solid_botton_btn)
    })
}
function setSolidActive(index,solid_tag,solid_botton_btn){
    $(solid_tag).eq(index).show().siblings().hide();
    $(solid_botton_btn).eq(index).addClass("active").siblings().removeClass("active");
}