var parent = $("body");
function getdata(data){
    var data = data;
    if(Number(data.state) == 10000 ){
        //  {"state": 10000,"msg": "成功","result":[{title:"中兴通讯回复",time:"2016-01-25"},{title:"中兴通讯回复",time:"2016-01-25"}]}
        var datas = data.result;
        var str = '';
        for( var i in datas){
            str +='<div class="xsh_noticebox"><a href=""><p class="xsh_notice_text">'+(datas[i].title)+'</p> <p class="xsh_notice_text xsh_notice_time">'+(datas[i].time)+'</p> </a></div>';
        }
        parent.append(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}
function reload(data){
    var data = data;
    if(Number(data.state) == 10000 ){
        //  {"state": 10000,"msg": "成功","result":[{title:"中兴通讯回复",time:"2016-01-25"},{title:"中兴通讯回复",time:"2016-01-25"}]}
        var datas = data.result;
        var str = '';
        for( var i in datas){
            str +='<div class="xsh_noticebox"><a href=""><p class="xsh_notice_text">'+(datas[i].title)+'</p> <p class="xsh_notice_text xsh_notice_time">'+(datas[i].time)+'</p> </a></div>';
        }
        parent.html(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}
