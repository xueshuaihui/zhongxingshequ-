var parent = $("body");
function getdata(data){
    var data = JSON.parse(data);
    if(Number(data.state) == 10000 ){
        //  {"state": 10000,"msg": "成功","result":[{head:"http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg",name:"中兴通讯回复",text:"中兴通讯中兴通讯中兴通讯中兴通讯",time:"2016-01-25"}]}
        var datas = data.result;
        var str = '';
        for( var i in datas){
            str +='<div class="private_letter"><div class="xsh_private_letter_box"><img src="'+(datas[i].head)+'" alt="" class="xsh_user_logo"><div><a href=""><h3 class="xsh_private_letter_name">'+(datas[i].head)+'</h3><p class="xsh_notice_text xsh_remind_text xsh_private_letter_text">'+(datas[i].text)+'</p></a></div><p class="xsh_notice_text xsh_notice_time">'+(datas[i].time)+'</p></div></div>';
        }
        parent.append(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}
function reload(data){
    var data = JSON.parse(data);
    if(Number(data.state) == 10000 ){
        //  {"state": 10000,"msg": "成功","result":[{head:"http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg",name:"中兴通讯回复",text:"中兴通讯中兴通讯中兴通讯中兴通讯",time:"2016-01-25"}]}
        var datas = data.result;
        var str = '';
        for( var i in datas){
            str +='<div class="private_letter"><div class="xsh_private_letter_box"><img src="'+(datas[i].head)+'" alt="" class="xsh_user_logo"><div><a href=""><h3 class="xsh_private_letter_name">'+(datas[i].head)+'</h3><p class="xsh_notice_text xsh_remind_text xsh_private_letter_text">'+(datas[i].text)+'</p></a></div><p class="xsh_notice_text xsh_notice_time">'+(datas[i].time)+'</p></div></div>';
        }
        parent.html(str);
    }else{
        window.location.href = "zxbbs://alert/"+data.msg;
    }
}