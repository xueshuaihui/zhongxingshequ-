/*获取圈子fid*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    hrefdada[arr[0]] = arr[1];
}
var fid = hrefdada.fid;
var uid = hrefdada.uid;
/**/
$(".xsh_revise").on("tap",function(){
    var dom = $(this);
    var circle_syn = dom.find(".xsh_revise_text");
    var href = dom.parent("a").attr("href");
    var text = circle_syn.html();
    if(href){
        window.location.href = href+"/fid="+fid+"&content="+text;
    }
})
/**/
var new_uid
var circle_member_one = $(".xsh_circle_member_one");
circle_member_one.on("tap",function(){
    new_uid = $(this).attr("new_uid");
})
function istrue(){
    $.ajax({
        url:"http://zte.rmbplus.com/app.php?action=circle-changeCircleFounder",
        type:'post',
        data:{old_uid:uid,new_uid:new_uid,fid:fid},
        success:function(result){
            if(result.state == 10000){
                var href = window.location.href.split("?")[0]+"?show=page-pageList&fid="+(fid)+"&tid=1&uid="+uid;
                var url = escape(href.replace(/\//g,"##"));
                window.location.href = "zxbbs://jump/"+url;
            }else{
                window.location.href = "zxbbs://alert/"+result.msg;
            }
        }
    })
}