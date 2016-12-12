var xshsearch = $("#xsh_search");
var asynchronousearchbox = $(".xsh_asynchronous_searchbox");
xshsearch.focus(function(){
    $(this).keyup(function(){
        var val = $(this).val();
        if(val){
            /*获取数据*/
            asynchronousearchbox.css({display:"block"});
            $.ajax({
                url:"http://zte.rmbplus.com/app.php?action=circle-circleSearch",
                data:{keyword:val},
                success:function(results){
                    var results = results;
                    if(results.state == 10000){
                        var data = results.result;
                        var str = '<div class="xsh_circle_list_box"><ul>';
                        for(var i in data){
                            str = strfun(str,data,i);
                            //str +='<li class="private_letter"><div class="xsh_private_letter_box xsh_circle_list"><a href=""><img src="http://pic.58pic.com/58pic/15/68/85/81c58PICK34_1024.jpg" alt="" class="xsh_user_logo xsh_user_logo_radius"></a><p class="xsh_circle_label">[典型案例] <img src="/h5/images/hot.png" alt="" class="xsh_hotimg"></p><h3 class=" xsh_text_one">典型案例典型案例典型案例典型案例典型案例典型案例典型案例</h3><span class="xsh_circle_name">二级开发和</span><span class="xsh_notice_time xsh_circle_time">2016-08-15 09:25:62</span></div></li>'
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
    if(result.state == 10000){
        var data = result.result;
        var str = '';
        for(var i in data){
            strfun(str,data,i);
            //str +='<li class="private_letter"><div class="xsh_private_letter_box xsh_circle_list"><a href=""><img src="http://pic.58pic.com/58pic/15/68/85/81c58PICK34_1024.jpg" alt="" class="xsh_user_logo xsh_user_logo_radius"></a><p class="xsh_circle_label">[典型案例] <img src="/h5/images/hot.png" alt="" class="xsh_hotimg"></p><h3 class=" xsh_text_one">典型案例典型案例典型案例典型案例典型案例典型案例典型案例</h3><span class="xsh_circle_name">二级开发和</span><span class="xsh_notice_time xsh_circle_time">2016-08-15 09:25:62</span></div></li>'
        }
        circle_list_box.append(str);
    }else{
        /*获取数据出错*/
        asynchronousearchbox.text(result.msg);
    }
}
function reload(result){
    if(result.state == 10000){
        var data = result.result;
        var str = '';
        for(var i in data){
            strfun(str,data,i);
            //str +='<li class="private_letter"><div class="xsh_private_letter_box xsh_circle_list"><a href=""><img src="http://pic.58pic.com/58pic/15/68/85/81c58PICK34_1024.jpg" alt="" class="xsh_user_logo xsh_user_logo_radius"></a><p class="xsh_circle_label">['+(data[i].name)+'] <img src="/h5/images/hot.png" alt="" class="xsh_hotimg"></p><h3 class=" xsh_text_one">'+(data[i].subject)+'</h3><span class="xsh_circle_name">'+(data[i].author)+'</span><span class="xsh_notice_time xsh_circle_time">'+(gettime(data[i].lastpost))+'</span></div></li>'
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
    return str +='<li class="private_letter"><div class="xsh_private_letter_box xsh_circle_list"><a href=""><img src="http://pic.58pic.com/58pic/15/68/85/81c58PICK34_1024.jpg" alt="" class="xsh_user_logo xsh_user_logo_radius"></a><p class="xsh_circle_label">['+(data[i].name)+'] <img src="/h5/images/hot.png" alt="" class="xsh_hotimg"></p><h3 class=" xsh_text_one">'+(data[i].subject)+'</h3><span class="xsh_circle_name">'+(data[i].author)+'</span><span class="xsh_notice_time xsh_circle_time">'+(gettime(data[i].lastpost))+'</span></div></li>'
}