/*获取圈子fid*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    hrefdada[arr[0]] = arr[1];
}
var myid = hrefdada.myid;
var uid = hrefdada.uid;
var xsh_addfriend = $(".xsh_addfriend");
xsh_addfriend.on("tap",function(){
    $.ajax({
        url:"app.php?action=member-applyFriend",
        data:{uid:myid,who:uid},
        success:function(result){
            if(result.state == 10000){
                window.location.href = "zxbbs:///finish";
            }else{
                window.location.href = "zxbbs://alert/"+result.msg;
            }
        }
    })
})