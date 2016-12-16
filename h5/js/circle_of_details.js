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
var xshsearch = $("#xsh_search");
var asynchronousearchbox = $(".xsh_asynchronous_searchbox");
xshsearch.focus(function(){
    $(this).on("keyup keydown",function(){
        var val = $(this).val();
        if(val){
            /*获取数据*/
            asynchronousearchbox.css({display:"block",height:$(".xsh_circle_list_box").height(),marginTop:"57px"});
            $.ajax({
                url:"/app.php?action=page-threadSearch",
                data:{keyword:val,fid:fid,uid:uid},
                type:"post",
                success:function(results){
                    var results = results;
                    if(results.state == 10000){
                        var data = results.result;
                        var str = '<div class="xsh_circle_list_box" style="background: #ffffff"><ul>';
                        for(var i in data){
                            str = strfun(str,data,i);
                        }
                        str +='</ul></div>';
                        asynchronousearchbox.html(str);
                    }else{
                        /*获取数据出错*/
                        asynchronousearchbox.text(results.msg);
                    }
                }
            })
        }else{
            asynchronousearchbox.html("");
            asynchronousearchbox.css({display:"none"});
        }
    })
})
/**/
var circle_list_box = $(".xsh_circle_list_box >ul");
function getdata(result){
    var result = JSON.parse(result);
    if(result.state == 10000){
        var data = result.result;
        var str = '';
        for(var i in data){
            str = strfun(str,data,i);
        }
        circle_list_box.append(str);
    }else{
        /*获取数据出错*/
        asynchronousearchbox.text(result.msg);
    }
}
function reload(result){
    var result = JSON.parse(result);
    if(result.state == 10000){
        var data = result.result;
        var str = '';
        for(var i in data){
            str = strfun(str,data,i);
        }
        circle_list_box.html(str);
    }else{
        /*获取数据出错*/
        asynchronousearchbox.text(result.msg);
    }
}

function gettime(time){
    return new Date(parseInt(time) * 1000).toLocaleString().replace(/\//g,"-").slice(0,11)+new Date(parseInt(time) * 1000).toTimeString().slice(0,8);
}
function strfun(str,data,i){
    var details = '/app.php?show=member-details&uid'+data[i].authorid;
    str +='<li class="private_letter" tid="'+(data[i].tid)+'"><div class="xsh_private_letter_box xsh_circle_list"><a href="'+(escape(details).replace(/\//g,"##"))+'" authorid="'+(data[i].authorid)+'"><img src="'+(data[i].icon)+'" alt="" class="xsh_user_logo xsh_user_logo_radius"></a><p class="xsh_circle_label">['+(data[i].name)+']';
    switch (data[i].stamp){
        case "0":str +='<img src="/static/h5/images/jh@2x.png" alt="" class="xsh_hotimg">';break;
        case "1":str +='<img src="/static/h5/images/rt@2x.png" alt="" class="xsh_hotimg">';break;
        case "2":str +='<img src="/static/h5/images/mt@2x.png" alt="" class="xsh_hotimg">';break;
        case "3":str +='<img src="/static/h5/images/yx@2x.png" alt="" class="xsh_hotimg">';break;
        case "4":str +='<img src="/static/h5/images/zd@2x.png" alt="" class="xsh_hotimg">';break;
        case "5":str +='<img src="/static/h5/images/tj@2x.png" alt="" class="xsh_hotimg">';break;
        case "6":str +='<img src="/static/h5/images/yc@2x.png" alt="" class="xsh_hotimg">';break;
        case "7":str +='<img src="/static/h5/images/bztj@2x.png" alt="" class="xsh_hotimg">';break;
        case "8":str +='<img src="/static/h5/images/bl@2x.png" alt="" class="xsh_hotimg">';break;
        case "19":str +='<img src="/static/h5/images/bj@2x.png" alt="" class="xsh_hotimg">';break;
        default:str +='';break;
    }
    var tzxq = '/app.php?show=page-pageContent&fid='+fid+'&tid='+(data[i].tid)+'&uid='+uid;
    str +='</p><a href="'+(escape(tzxq.replace(/\//g,"##")))+'" ><p class=" xsh_text_one" style="background: '+(data[i].bgcolor)+';color: '+(data[i].color)+';">';
    if(data[i].B){
        str += '<b>'+(data[i].subject)+'</b>';
    }else if(data[i].I){
        str += '<i>'+(data[i].subject)+'</i>';
    }else if(data[i].U){
        str += '<u>'+(data[i].subject)+'</u>';
    }else{
        str += (data[i].subject);
    }
    str +='</p></a><span class="xsh_circle_name">'+(data[i].author)+'</span><span class="xsh_notice_time xsh_circle_time">'+(gettime(data[i].lastpost))+'</span></div></li>';
    return str;
}
