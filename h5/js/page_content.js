/*获取圈子fid*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    hrefdada[arr[0]] = arr[1];
}
var fid = hrefdada.fid;
var uid = hrefdada.uid;
var tid = hrefdada.tid;
/*对帖子回复*/
var messagefixed = $(".xsh_floor_text");
messagefixed.on("tap",function(){
    window.location.href = "zxbbs://post/reply/uid="+uid+"&fid="+fid+"&tid="+tid;
})
/*对楼层回复*/
var floortextbox =$(".xsh_floor_textbox");
floortextbox.on("tap",function(){
    var pid = $(this).parents(".xsh_floor").attr("pid");
    window.location.href = "zxbbs://post/reply/uid="+uid+"&fid="+fid+"&pid="+pid+"&tid="+tid;
})
/*回复的数据*/
var floorbox = $(".xsh_floor_box>ul");
function replydata(result){
    var result = JSON.parse(result);
        /*插入到1楼前*/
        var str='<li class="xsh_floor" pid="'+(result.pid)+'"><a href="" uid="'+(uid)+'"><img src="'+(result.portrait)+'" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a><p><span class="xsh_floor_username">'+(result.name)+'：</span><span class="xsh_floor_number"></span></p><div class="xsh_floor_textbox"><p class="xsh_floor_text">'+(result.text)+'</p><ul>';
        var length = result.images.length;
        for (var i in result.images){
            if(length ==2||length ==4){
                str +='<li class="xsh_floor_text_img xsh_floor_text_img_two"><a href="javascript:;">';
            }else if(length == 1){
                str +='<li class="xsh_floor_text_img xsh_floor_text_img_one"><a href="javascript:;">';
            }else{
                str +='<li class="xsh_floor_text_img"><a href="javascript:;">';
            }
            str +='<img src="'+(result.images[i])+'" alt=""></a></li>';
        }
        str +='</ul><span class="xsh_floor_text_time">'+(result.time)+'</span></div></li>';
        floorbox.prepend(str);
}

//        var a = {pid:"3243",name:"头发发给",portrait:"http://zte.rmbplus.com/uc_server/avatar.php?uid=1&size=small" ,text:"听到好音乐。它所具备的加密功能、超长续航、高清录音以及高清拍摄等等等优质功能，足够令其高效率的协助执法人员完成高效、规范执法的重任",time:"2016-15-48",images:["http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg","http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg","http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg","http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg","http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg"]}
/*上拉加载*/
function getdata(results){
    var results = JSON.parse(results);
    if(results.state == 10000){
        var data = results.result;
        for(var i in data){
            var str='<li class="xsh_floor" pid="'+(data[i].pid)+'"><a href="" uid="'+(data[i].authorid)+'"><img src="'+(data[i].usericon)+'" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a><p><span class="xsh_floor_username">'+(data[i].author)+'：</span><span class="xsh_floor_number"></span></p><div class="xsh_floor_textbox"><p class="xsh_floor_text">'+(data[i].message)+'</p><ul>';
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
            str +='</ul><span class="xsh_floor_text_time">'+(result.time)+'</span></div></li>';
        }
    }else{
        window.location.href = "zxbbs://alert/"+results.msg;
    }
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
$(".xsh_floor .xsh_floor_text_img").on("tap",function(){
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