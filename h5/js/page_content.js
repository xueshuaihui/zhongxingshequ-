/*获取圈子fid*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    hrefdada[arr[0]] = arr[1];
}
var http = window.location.href.split('/app')[0];
var fid = hrefdada.fid;
var uid = hrefdada.uid;
var tid = hrefdada.tid;
/*对帖子回复*/
var messagefixed = $(".xsh_message_fixed");
messagefixed.on("tap",function(){
    window.location.href = "zxbbs://post/reply/uid="+uid+"&fid="+fid+"&tid="+tid;
})
/*对楼层回复*/
var floorbox = $(".xsh_floor_box>ul");
var floortextbox =$(".xsh_floor_textbox");
floorbox.on("tap",".xsh_floor_text",function(){
    var pid = $(this).parents(".xsh_floor").attr("pid");
    window.location.href = "zxbbs://post/reply/uid="+uid+"&fid="+fid+"&pid="+pid+"&tid="+tid;
})
function gettime(time){
    return new Date(parseInt(time) * 1000).toLocaleDateString().replace(/\//g,"-")+" "+new Date(parseInt(time) * 1000).toTimeString().slice(0,8);
}
/*回复的数据*/
function replydata(result){
    alert(eval(result))
    alert(JSON.parse(result))
    var results = JSON.parse(result);
    /*插入到1楼前*/
    var data = results.result;
    var str = '';
    var ziliao = http+'/app.php?show=member-details&uid='+uid;
    str +='<li class="xsh_floor" pid="'+(data.pid)+'"><a name="'+(uid)+'" href="zxbbs://jump/'+(escape(ziliao.replace(/\//g,"##")))+'" uid="'+(data.authorid)+'"><img src="'+(data.usericon)+'" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a><p><span class="xsh_floor_username">'+(data.author)+'：</span><span class="xsh_floor_number"></span></p><div class="xsh_floor_textbox">';
    if(data.reply){
        str +='<div class="reply"><p>'+(data.reply.split("\n")[0])+'</p><p>'+(data.reply.split("\n")[1])+'</p></div>';
        }
        str +='<p class="xsh_floor_text">'+(data.message)+'</p><ul>';
        var images=[];
        var att = data.attach;
        for (var j in att){
            if(att[j].isimage == "1"){
                 images.push(att[j].attachment);
            }
        }
    var length = images.length;
    for (var i in images){
        if(length ==2||length ==4){
            str +='<li class="xsh_floor_text_img xsh_floor_text_img_two"><a href="javascript:;">';
        }else if(length == 1){
            str +='<li class="xsh_floor_text_img xsh_floor_text_img_one"><a href="javascript:;">';
        }else{
            str +='<li class="xsh_floor_text_img"><a href="javascript:;">';
        }
            str +='<img src="'+(images[i])+'" alt=""></a></li>';
    }
     str +='</ul><span class="xsh_floor_text_time">'+(gettime(data.dateline))+'</span></div></li>';
    floorbox.prepend(str);
    window.location.href = "#"+uid;
}
var scheight = $(window).height();
var reloadbox = $(".reloadbox");
var t1 = $(".xsh_postdetails_box");
var t2 = $(".xsh_postdetails_textbox");
var t3 = $(".xsh_floor_box")
window.onscroll = function(){
    var height = t1.height()+t2.height()+t3.height();
    var scrolltop = $(window).scrollTop();
    if(Math.abs(height-scheight-scrolltop) <= 50){
        reloadbox.css({display:"block"});
        /*上拉*/
        shangla();
    }else{
        reloadbox.css({display:"none"});
        /*关闭*/
    }
}
/*上拉加载*/
function getdata(results){
    if(results.state == 10000){
        var data = results.result;
        var str = '';
        for(var i in data){
            var ziliao = http+'/app.php?show=member-details&uid='+data[i].authorid;
             str +='<li class="xsh_floor" pid="'+(data[i].pid)+'"><a href="zxbbs://jump/'+(escape(ziliao.replace(/\//g,"##")))+'" uid="'+(data[i].authorid)+'"><img src="'+(data[i].usericon)+'" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a><p><span class="xsh_floor_username">'+(data[i].author)+'：</span><span class="xsh_floor_number">'+(Number(data[i].position)-1)+'楼</span></p><div class="xsh_floor_textbox">';
            if(data[i].reply){
                str +='<div class="reply"><p>'+(data[i].reply.split("\n")[0])+'</p><p>'+(data[i].reply.split("\n")[1])+'</p></div>';
            }
            str +='<p class="xsh_floor_text">'+(data[i].message)+'</p><ul>';
            var images=[];
            var att = data[i].attach;
            for (var j in att){
                if(att[j].isimage == "1"){
                    images.push(att[j].attachment);
                }
            }
            var length = images.length;
            for (var i in images){
                if(length ==2||length ==4){
                    str +='<li class="xsh_floor_text_img xsh_floor_text_img_two"><a href="javascript:;">';
                }else if(length == 1){
                    str +='<li class="xsh_floor_text_img xsh_floor_text_img_one"><a href="javascript:;">';
                }else{
                    str +='<li class="xsh_floor_text_img"><a href="javascript:;">';
                }
                str +='<img src="'+(images[i])+'" alt=""></a></li>';
            }
            str +='</ul><span class="xsh_floor_text_time">'+(gettime(data[i].dateline))+'</span></div></li>';
        }
        floorbox.append(str);
        reloadbox.css({display:"none"});
        /*关闭*/
    }else{
        reloadbox.css({display:"none"});
        /*关闭*/
        window.location.href = "zxbbs://alert/"+results.msg;
    }
}
var page = 1;
function shangla(){
    page++;
    $.ajax({
        url:"/app.php?action=page-tieziList",
        type:"post",
        data:{tid:tid,fid:fid,page:page},
        success:function(result){
            getdata(result);
        }
    })
}

/*缩略图预览*/
var openPhotoSwipe = function(index,arr) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var items = arr;
    var options = {
        history: false,
        focus: false,
        showAnimationDuration: 0,
        hideAnimationDuration: 0,
        index:index
    };
    var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
};
floorbox.on("tap",".xsh_floor .xsh_floor_text_img",function(){
    var index = $(this).index();
    var data = $(this).parent("ul").find(".xsh_floor_text_img");
    var arr = [];
    var length = data.length;
    for(var i=0;i<length;i++){
        var imgsrc = {};
        var img = $(data[i]).find("a>img");
        imgsrc.src = img.attr("src");
        imgsrc.w = img[0].naturalWidth;
        imgsrc.h = img[0].naturalHeight;
        arr[i] = imgsrc;
    }
    openPhotoSwipe(index,arr);
})