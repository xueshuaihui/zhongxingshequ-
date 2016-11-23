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
    t = setInterval(car_move,5000);
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
/*专家互动*/
var expertFormHeader = $(".xsh_expert_form_header");
var expertRadio = $(".xsh_expert_radio");
var expertBox = $(".xsh_expert_box");
var expertTextarea = $(".xsh_expert_textarea");
/*获取表单*/
var expertForm = $(".xsh_expert_form");
var expertFormSubmit = $(".xsh_expert_form_submit");
function expertInteraction(hid){
    var hid=hid;
    $.ajax({
        url:"",
        type:"post",
        data:{hid:hid},
        success:function(result){
            //result={code:10000,data:{header:"无线",experts:[{id:13,name:"路由器/专家:dfjkg"},{id:13,name:"路由器/专家:dfjkg"},{id:13,name:"路由器/专家:dfjkg"}]}}
            if(result.code == 10000){
                var header = result.data.header;
                expertFormHeader.children("h3").text(header);
                var data = result.data.experts;
                var str = "";
                for(var i in data){
                    str+='<label><input name="experts" type="radio" value="'+(data[i].id)+'" />'+(data[i].name)+'</label>';
                }
                expertRadio.html(str);
                expertBox.css({display:"block"});
            }else{
                alert("请求失败稍后再试！")
            }
        }
    })
}
/*提交留言*/
function formsubmit(){
    var text = expertTextarea.children("textarea").val();
    var id = expertRadio.find("input:checked").val();
    var action = expertForm.attr("action");
    $.ajax({
        url:action,
        type:"post",
        data:{id:id,text:text},
        success:function(result){
            if(result==10000){
                /*成功*/
                closeform();
                alert("发送成功！")
            }else{
                /*失败*/
                alert("网络不佳，请稍后再试");
                return;
            }
        }
    })
    expertForm.submit(function(){
        return false;
    });
}
function closeform(){
    expertFormHeader.children("h3").text("");
    expertRadio.html("");
    expertBox.css({display:"none"});
}
/*划上去显示标签卡*/
var user_data = $(".xsh_user_data");
$(".xsh_user_box").hover(function(){
    user_data.css({display:"block"});
},function(){
    user_data.css({display:"none"});
})
var expertCad = $(".xsh_expert_cad");
var expert_name = $(".xsh_expert_name");
expert_name.each(function(index,obj){
    $(this).hover(function(){
        expertCad.eq(index).css({display:"block"});
    },function(){
        expertCad.eq(index).css({display:"none"});
    })
})
/*tab*/
var tab_title = $(".xsh_tab_title");
var tab_content = $(".xsh_tab_con");
var tabid,box = tab_content.eq(0).children("ul");
tab_title.each(function(index,obj){
    $(this).on("click",function(){
        tab_title.removeClass("xsh_tab_title_hot");
        tabid = $(this).addClass("xsh_tab_title_hot").attr("id");
        tab_content.removeClass("xsh_tab_now").eq(index).addClass("xsh_tab_now");
        box = tab_content.eq(index).children("ul");
        if(!box.children()){
            ajaxtext(box,tabid,1);
        }
    })
})
/*点击页码*/
function asyncLoad(page){
    ajaxtext(box,tabid,page);
}
/*异步加载请求*/
function ajaxtext(box,tab,page){
    /*box:数据插入的地方
    * tab:tab栏的位置
    * page:页码
    * */
    $.ajax({
        url:"ztindex.php?api=1&t="+tab+"&p="+page,
        type:"post",
        success:function(result){
            if(result.code == 10000){
            // {code:100000,data:[{img:"http://img.huafans.cn/data/attachment/portal/201611/18/155603xcqn2hwg6cxbnnji.jpg",href:"www.baidu.com",theme:"图片提示",title:"文章标题",syn:"帖子简介",time:"发帖时间",num:"点击量"}]}
                var data = result.data;
                var str='';
                for(var i in data){
                    str+='<li class="xsh_tab_conbox"><div class="xsh_tab_img"><a href="'+(data[i].href)+'" target="_blank"><img src="'+(data[i].img)+'" alt="加载不成功" title="'+(data[i].theme)+'"></a></div><div class="xsh_tab_writing"><div class="xsh_tab_contitle"><a href="'+(data[i].href)+'" target="_blank"><p>'+(data[i].title)+'</p></a></div><div class="xsh_tab_concise"><p>'+(data[i].syn)+'<span class="xsh_tab_more"><a href="'+(data[i].href)+'">全文<img src="/static/zte/images/u76.png" alt=""></a></span></p></div><div class="xsh_tab_time"><span>'+(data[i].time)+'</span><span class="liulanl">'+(data[i].num)+'</span></div></div></li>';
                }
               box.html(str);
            }else{
                alert("网络错误！");
                return;
            }
        }
    })
}
function addfriend1(){
    /*未登录*/
    alert("登录")
}
function addfriend2(){
    if(confirm("确认添加为好友？")){
        $.ajax({
            url:"",
            data:{userid:312},
            type:"post",
            success:function(result){
                if(result.code==10000){
                    alert(code.mg)
                }else{
                    alert("网络错误，请稍后重试！")
                }
            }
        })
    }
}