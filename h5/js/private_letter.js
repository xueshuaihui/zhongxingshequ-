/*获取圈子fid*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    hrefdada[arr[0]] = arr[1];
}
var http = window.location.href.split('/app')[0];
var uid = hrefdada.uid;
function gettime(time){
    return new Date(parseInt(time) * 1000).toLocaleDateString().replace(/\//g,"-")+" "+new Date(parseInt(time) * 1000).toTimeString().slice(0,8);
}
var parent = $("body");
function getdata(data){
    var data = JSON.parse(data);
    if(Number(data.state) == 10000 ){
        var datas = data.result;
        var str = '';
        for( var i in datas){
            var message = http+'/app.php?show=message-pmc&uid='+uid+'&touid='+(datas[i].touid)+'&page=1';
            str +='<div class="private_letter"><div class="xsh_private_letter_box"><img src="'+(datas[i].you)+'" alt="" class="xsh_user_logo"><div><a href="zxbbs://jump/'+(escape(message.replace(/\//g,"##")))+'"><h3 class="xsh_private_letter_name">'+(datas[i].lastauthor)+'</h3><p class="xsh_notice_text xsh_remind_text xsh_private_letter_text">'+(datas[i].message)+'</p></a></div><p class="xsh_notice_text xsh_notice_time">'+(gettime(datas[i].lastupdate))+'</p></div></div>';
        }
        parent.append(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}
function reload(data){
    var data = JSON.parse(data);
    if(Number(data.state) == 10000 ){
        var datas = data.result;
        var str = '';
        for( var i in datas){
            var message = http+'/app.php?show=message-pmc&uid='+uid+'&touid='+(datas[i].touid)+'&page=1';
            str +='<div class="private_letter"><div class="xsh_private_letter_box"><img src="'+(datas[i].you)+'" alt="" class="xsh_user_logo"><div><a href="zxbbs://jump/'+(escape(message.replace(/\//g,"##")))+'"><h3 class="xsh_private_letter_name">'+(datas[i].lastauthor)+'</h3><p class="xsh_notice_text xsh_remind_text xsh_private_letter_text">'+(datas[i].message)+'</p></a></div><p class="xsh_notice_text xsh_notice_time">'+(gettime(datas[i].lastupdate))+'</p></div></div>';
        }
        parent.html(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}