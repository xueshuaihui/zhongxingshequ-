var conleft = $(".xsh_con_left");
/*轮播图框*/
var carousel = $(".xsh_carousel");
/*轮播图*/
var carimg = $(".xsh_carimg");
var max_carnum = carimg.length;
/*标识符*/
var car_radius = $(".xsh_car_radius");
/*左右按钮*/
var car_button = $(".xsh_car_button");
var car_buttonl = $(".xsh_car_buttonl");
var car_buttonr = $(".xsh_car_buttonr");
/*标识符*/
var car_radbox = $(".xsh_car_radbox");
/*轮播图大于一张执行轮播*/
if( max_carnum > 1){
    car_button.css({display:"block"});
    car_radbox.css({display:'block'});
    var t = setInterval(car_move,2000);
    $(".xsh_carousel").hover(function(){
        carstiop();
    },function(){
        carstart()
    });
}
var car_num = 0;
function car_move(){
    /*图片轮播*/
    carimg.eq(car_num).fadeOut(200,"linear",function(){});
    car_num++;
    if(car_num>=max_carnum){
        car_num=0;
    }
    carimg.eq(car_num).fadeIn(200,"linear",function(){});
    /*标识符轮播*/
    carradiu_move();
}
/*标识符轮播*/
function carradiu_move(){
    car_radius.removeClass("xhs_car_hot");
    car_radius.eq(car_num).addClass("xhs_car_hot");
}
/*划入暂停*/
function carstiop(){
    clearInterval(t);
}
/*滑出开始*/
function carstart(){
    t = setInterval(car_move,2000);
}
/*点击左右按钮*/
car_buttonl.on("click",function(){
    carstiop();
    /*图片上翻*/
    carimg.eq(car_num).fadeOut(200,"linear",function(){});
    car_num--;
    if(car_num < 0){
        car_num=max_carnum-1;
    }
    carimg.eq(car_num).fadeIn(200,"linear",function(){});
    /*标识符上翻*/
    carradiu_move();
})
car_buttonr.on("click",function(){
    carstiop();
    /*图片下翻*/
    carimg.eq(car_num).fadeOut(200,"linear",function(){});
    car_num++;
    if(car_num>=max_carnum){
        car_num=0;
    }
    carimg.eq(car_num).fadeIn(200,"linear",function(){});
    /*标识符下翻*/
    carradiu_move();
})
/*点击标识符*/
car_radius.each(function(index,obj){
    $(this).on("click",function(){
        carstiop();
        /*图片轮播*/
        carimg.eq(car_num).fadeOut(200,"linear",function(){});
        car_num = index;
        carimg.eq(car_num).fadeIn(200,"linear",function(){});
        /*标识符轮播*/
        carradiu_move();
    })
})
/*tab*/

