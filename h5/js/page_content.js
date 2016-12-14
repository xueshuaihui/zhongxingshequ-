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
var messagefixed = $(".xsh_message_fixed");
messagefixed.on("tap",function(){
    window.location.href = "zxbbs://post/new/uid="+uid+"&fid="+fid+"&tid="+tid;
})
/*对楼层回复*/
var floortextbox =$(".xsh_floor_textbox");
floortextbox.on("tap",function(){
    var pid = $(this).parents(".xsh_floor").attr("pid");
    window.location.href = "zxbbs://post/new/uid="+uid+"&fid="+fid+"&pid="+pid;
})
/*回复的数据*/
var floorbox = $(".xsh_floor_box>ul");
function getdata(result){
    var result = JSON.parse(result);
    if(result.state== 10000){
        /*插入到1楼前*/
        var str='<li class="xsh_floor" pid="3"><a href=""><img src="http://zte.rmbplus.com/uc_server/avatar.php?uid=1&size=small" alt="" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a><p><span class="xsh_floor_username">头发发给：</span><span class="xsh_floor_number">0楼</span></p><div class="xsh_floor_textbox"><p class="xsh_floor_text">听到好音乐。它所具备的加密功能、超长续航、高清录音以及高清拍摄等等等优质功能，足够令其高效率的协助执法人员完成高效、规范执法的重任</p><ul><li class="xsh_floor_text_img"><a href="javascript:;"><img src="http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg" alt=""></a></li><li class="xsh_floor_text_img"><a href="javascript:;"><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></a></li><li class="xsh_floor_text_img"><a href="javascript:;"><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></a></li></ul><span class="xsh_floor_text_time">2016-15-48</span></div></li>';
        /*
        * <li class="xsh_floor" pid="3"><a href=""><img src="http://zte.rmbplus.com/uc_server/avatar.php?uid=1&size=small" alt="" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a><p><span class="xsh_floor_username">头发发给：</span><span class="xsh_floor_number">3楼</span></p><div class="xsh_floor_textbox"><p class="xsh_floor_text">听到好音乐。它所具备的加密功能、超长续航、高清录音以及高清拍摄等等等优质功能，足够令其高效率的协助执法人员完成高效、规范执法的重任</p><ul><li class="xsh_floor_text_img"><a href="javascript:;"><img src="http://img.pconline.com.cn/images/upload/upc/tx/wallpaper/1610/31/c6/29213507_1477922959573_800x800.jpg" alt=""></a></li><li class="xsh_floor_text_img"><a href="javascript:;"><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></a></li><li class="xsh_floor_text_img"><a href="javascript:;"><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></a></li></ul><span class="xsh_floor_text_time"><?echo date('Y-m-d H:i:s', $value['dateline'])?></span></div></li>
        * */
        floorbox.prepend(str);
    }else{
        window.location.href = "zxbbs://alert/"+result.msg;
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