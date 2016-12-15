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
var new_uid
var circle_member_one = $(".xsh_circle_member_one");
circle_member_one.on("tap",function(){
    new_uid = $(this).attr("new_uid");
    var name = $(this).children(".xsh_circle_member_name").text();
    window.location.href =  "zxbbs://alert/确定将圈主转让给'"+(name)+"'？/取消/确定";
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