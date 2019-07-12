var class_solid_index = 0;
                var class_solid_size = $(".class-solid-data").size();
                var class_solid_interval;

                $(function(){

                    showIndex();

                    class_solid_interval = setInterval(function(){
                        solid_interval();
                    },5000)


                    $("#solidL").click(function(){

                        clearInterval(class_solid_interval);

                        class_solid_index --;
                        if(class_solid_index < 0){
                            class_solid_index = class_solid_size-1;
                        }
                        showIndex();

                        class_solid_interval = setInterval(function(){
                            solid_interval();
                        },5000)
                    })


                    $("#solidR").click(function(){

                        clearInterval(class_solid_interval);

                        class_solid_index ++;
                        if(class_solid_index == class_solid_size){
                            class_solid_index = 0;
                        }

                        showIndex();

                        class_solid_interval = setInterval(function(){
                            solid_interval();
                        },5000)
                    })

                    $(".solid-bottom-icon .btn").hover(function(){
                        clearInterval(class_solid_interval);
                        class_solid_index = $(this).index();
                        showIndex();

                        class_solid_interval = setInterval(function(){
                            solid_interval();
                        },5000)
                    })
                    
                })



                function solid_interval(){

                    class_solid_index ++;

                    if(class_solid_index == class_solid_size){
                        class_solid_index = 0;
                    }else if(class_solid_index < 0){
                        class_solid_index = class_solid_size-1;
                    }

                    showIndex();
                }


                function showIndex(){
                    $(".class-solid-data").eq(class_solid_index).addClass("active").show().siblings().removeClass("active").hide();
                    $(".solid-bottom-icon .btn").eq(class_solid_index).addClass("active").siblings().removeClass("active");
                }