var jq = jQuery.noConflict();
var conleft = jq(".xsh_con_left");
/*轮播图框*/
var carousel = jq(".xsh_carousel");
/*轮播图*/
var carimg = jq(".xsh_carimg");
var max_carnum = carimg.length;
/*标识符*/
var car_radius = jq(".xsh_car_radius");
/*左右按钮*/
var car_button = jq(".xsh_car_button");
var car_buttonl = jq(".xsh_car_buttonl");
var car_buttonr = jq(".xsh_car_buttonr");
/*标识符*/
var car_radbox = jq(".xsh_car_radbox");
/*轮播图大于一张执行轮播*/
if( max_carnum > 1){
    car_button.css({display:"block"});
    car_radbox.css({display:'block'});
    var t = setInterval(car_move,5000);
    jq(".xsh_carousel").hover(function(){
        carstiop();
    },function(){
        carstart()
    });
}
var car_num = 0;
function car_move(){
    /*图片轮播*/
    carimg.eq(car_num).fadeOut(200,"linear");
    car_num++;
    if(car_num>=max_carnum){
        car_num=0;
    }
    carimg.eq(car_num).fadeIn(200,"linear");
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
    carimg.eq(car_num).fadeOut(200,"linear");
    car_num--;
    if(car_num < 0){
        car_num=max_carnum-1;
    }
    carimg.eq(car_num).fadeIn(200,"linear");
    /*标识符上翻*/
    carradiu_move();
})
car_buttonr.on("click",function(){
    carstiop();
    /*图片下翻*/
    carimg.eq(car_num).fadeOut(200,"linear");
    car_num++;
    if(car_num>=max_carnum){
        car_num=0;
    }
    carimg.eq(car_num).fadeIn(200,"linear");
    /*标识符下翻*/
    carradiu_move();
})
/*点击标识符*/
car_radius.each(function(index,obj){
    jq(this).on("click",function(){
        carstiop();
        /*图片轮播*/
        carimg.eq(car_num).fadeOut(200,"linear");
        car_num = index;
        carimg.eq(car_num).fadeIn(200,"linear");
        /*标识符轮播*/
        carradiu_move();
    })
})
/*专家互动*/
var expertFormHeader = jq(".xsh_expert_form_header");
var expertRadio = jq(".xsh_expert_radio");
var expertBox = jq(".xsh_expert_box");
var expertTextarea = jq(".xsh_expert_textarea");
/*获取表单*/
var expertForm = jq(".xsh_expert_form");
var expertFormSubmit = jq(".xsh_expert_form_submit");
function expertInteraction(hid){
    var hid=hid;
    jq.ajax({
        url:"ztindex.php?api=1&thread="+hid,
        type:"get",
        success:function(result){
            //result={code:10000,data:{header:"无线",experts:[{id:13,name:"路由器/专家:dfjkg"},{id:13,name:"路由器/专家:dfjkg"},{id:13,name:"路由器/专家:dfjkg"}]}}
            result = JSON.parse(result);
            if(result.state == 1){
                var header = result.data.header;
                expertFormHeader.children("h3").text(header);
                expertForm.children("form").action = "forum.php?mod=post&amp;infloat=yes&amp;action=newthread&amp;fid=38&amp;extra=&amp;topicsubmit=yes&amp;jet=rmbplus";
                var data = result.data.experts;
                var str = "";
                for(var i in data){
                    str+='<label><input name="experts" type="radio" value="'+(data[i].fid)+'" />'+(data[i].name)+'</label>';
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
    jq.ajax({
        url:action,
        type:"post",
        data:{id:id,text:text},
        success:function(result){
            result = JSON.parse(result);
            if(result.state==1){
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
var user_data = jq(".xsh_user_data");
jq(".xsh_user_box").hover(function(){
    user_data.css({display:"block"});
},function(){
    user_data.css({display:"none"});
})
var expertCad = jq(".xsh_expert_cad");
var expert_name = jq(".xsh_expert_name");
expert_name.each(function(index,obj){
    jq(this).hover(function(){
        expertCad.eq(index).css({display:"block"});
    },function(){
        expertCad.eq(index).css({display:"none"});
    })
})
/*tab*/
var tab_title = jq(".xsh_tab_title");
var tab_content = jq(".xsh_tab_con");
var tabid = tab_title.eq(0).attr("id");
var box = tab_content.eq(0).children("ul");
tab_title.each(function(index,obj){
    jq(this).on("click",function(){
        tab_title.removeClass("xsh_tab_title_hot");
        tabid = jq(this).addClass("xsh_tab_title_hot").attr("id");
        tab_content.removeClass("xsh_tab_now").eq(index).addClass("xsh_tab_now");
        box = tab_content.eq(index).children("ul");
        if(!box.html()){
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
    jq.ajax({
        url:"ztindex.php?api=1&t="+tab+"&p="+page,
        type:"get",
        success:function(result){
            result = JSON.parse(result);
            if(result.state == 1){
                var data = result.data;
                var str='';
                for(var i in data['data']){
                    str = '<li class="xsh_tab_conbox"><div class="xsh_tab_img"><a href="forum.php?mod=viewthread&tid='+(data['data'][i].tid)+'" target="'+target+'"><img src="'+(data['data'][i].image)+'" alt="加载不成功" title="'+(data['data'][i].subject)+'"></a></div><div class="xsh_tab_writing"><div class="xsh_tab_contitle"><a href="forum.php?mod=viewthread&tid='+(data['data'][i].tid)+'" target="'+target+'"><p>'+(data['data'][i].subject)+'</p></a></div><div class="xsh_tab_concise"><p>'+(data['data'][i].message)+'<span class="xsh_tab_more"><a href="forum.php?mod=viewthread&tid='+(data['data'][i].tid)+'" target="'+target+'">全文<img src="/static/zte/images/u76.png" alt=""></a></span></p></div><div class="xsh_tab_time"><span>'+(data['data'][i].dateline)+'</span><span class="liulanl">'+(data['data'][i].views)+'</span></div></div></li>' + str;
                }
                box.html(str);
                jq(".xsh_page_box ul").html(data['pagnate']);
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
        jq.ajax({
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