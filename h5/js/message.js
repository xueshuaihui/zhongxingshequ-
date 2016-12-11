/*处理获取到id信息*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    if(arr[1].mach('#xsh_foot')){
        arr[1] = arr[1].split("#"[0]);
    }
    hrefdada[arr[0]] = arr[1];
}
/**/
$(function(){
    var messagebox = $(".xsh_message_box>ul");
    /*图片预览*/
    messagebox.on("tap",".xsh_message_text p img",function(){
        var src = $(this).attr("src");
        var mask = $("<div>").css({width:"100%",height:"100%",position:"fixed",top:"0",left:"0",background:"#000000"}).appendTo($("body")).on("tap",function(){
            $(this).remove();
        });
        $("<image src='"+src+"' class='xsh_message_textmaximg'>").appendTo(mask);
    })
    /*获取聊天信息*/
    var usericon = '';
    function getdata(page){
        $.ajax({
            url:"http://zte.rmbplus.com/app.php?action=message-getPm",
            data:{uid:hrefdada.uid,page:page,touid:hrefdada.touid},
            success:function(result){
                var data = eval(result);
                if(data.state == 10000){
                    var datas = data.result;
                    var str = '';
                    for(var i in datas){
                        if(!usericon){
                            usericon = datas[i].me;
                        }
                        if(datas[i].position == "r"){
                            str += '<li class="xsh_me"><div class="xsh_message_logobox"><a href="zxbbs://jump/'+(escape())+'"><img src="'+(datas[i].me)+'" alt="">';
                        }else if(datas[i].position == "l"){
                            str += '<li class="xsh_you"><div class="xsh_message_logobox"><a href="zxbbs://jump/'+(escape())+'"><img src="'+(datas[i].you)+'" alt="">';
                        }
                        str +='</a></div><div class="xsh_message_text"><div class="xsh_you_trigon"></div><p>'+(datas[i].message)+'</p></div></li>';
                    }
                      str += '<a name="new">';
                    messagebox.insertBefore(str);
                }else{
                    window.location.href = "zxbbs://alert/"+data.msg;
                }
            }
        })
    }

getdata();
window.onscroll = function(){
    var top = $(window).scrollTop();
    if(top <= 100){
        getdata();
        window.location.href = "#new";
    }
}
/*发送私信*/
var message_submit = $(".xsh_message_submit");
var message_inputext = $(".xsh_message_inputext");
var xsh_foot = $("a[name=xsh_foot]");
    var num = 100;
message_submit.on("tap",function(){
    num++;
    var val = message_inputext.val();
    if(val){
        $.ajax({
            url:"http://zte.rmbplus.com/app.php?action=message-sendPm",
            data:{uid:hrefdada.uid,touid:hrefdada.touid,message:val},
            success:function(result){
                var data = eval(result);
                if(data.state == 10000){
                    var str = '';
                    str += '<li class="xsh_me"><div class="xsh_message_logobox"><a href="zxbbs://jump/'+(escape())+'" name ="'+(num)+'"><img src="'+(usericon)+'" alt=""></a></div><div class="xsh_message_text"><div class="xsh_you_trigon"></div><p>'+(val)+'</p></div></li>';
                    xsh_foot.before(str);
                    window.location.href = "#"+num;
                }else{
                    window.location.href = "zxbbs://alert/"+msg;
                }
            }
        })
    }else{
        window.location.href = "zxbbs://alert/请输入内容";
    }
})
    /*定位到最下面*/
    window.location.href = "#xsh_foot";
})