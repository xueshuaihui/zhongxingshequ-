/*获取圈子fid*/
var windowhrsf = window.location.href.split("?")[1].split("&");
var hrefdada = {};
for(var i in windowhrsf){
    var arr = windowhrsf[i].split("=");
    hrefdada[arr[0]] = arr[1];
}
var http = window.location.href.split('/app')[0];
var uid = hrefdada.uid;
var parent = $("body");
function getdata(data){
    var data = JSON.parse(data);
    if(Number(data.state) == 10000 ){
        //  {"state": 10000,"msg": "成功","result":[{title:"中兴通讯回复",time:"2016-01-25"},{title:"中兴通讯回复",time:"2016-01-25"}]}
        var datas = data.result;
        var str = '';
        for( var i in datas){
            var ggxq = http+'/app.php?show=message-ptc&mid='+uid;
            str +='<div class="xsh_noticebox"><a href="zxbbs://jump/'+(escape(ggxq.replace(/\//g,"##")))+'"><p class="xsh_notice_text">'+(datas[i].title)+'</p> <p class="xsh_notice_text xsh_notice_time">'+(datas[i].time)+'</p> </a></div>';
        }
        parent.append(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}
function reload(data){
    var data = JSON.parse(data);
    if(Number(data.state) == 10000 ){
        //  {"state": 10000,"msg": "成功","result":[{title:"中兴通讯回复",time:"2016-01-25"},{title:"中兴通讯回复",time:"2016-01-25"}]}
        var datas = data.result;
        var str = '';
        for( var i in datas){
            var ggxq = http+'/app.php?show=message-ptc&mid='+uid;
            str +='<div class="xsh_noticebox"><a href="zxbbs://jump/'+(escape(ggxq.replace(/\//g,"##")))+'"><p class="xsh_notice_text">'+(datas[i].title)+'</p> <p class="xsh_notice_text xsh_notice_time">'+(datas[i].time)+'</p> </a></div>';
        }
        parent.html(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}
