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
                    var results = Json.parse(results);
                    if(results.state == 10000){
                        var data = results.result;
                        var str = '<div class="xsh_circle_list_box"><ul>';
                        for(var i in data){
                            str +='<li class="private_letter"><div class="xsh_private_letter_box xsh_circle_list"><a href=""><img src="http://pic.58pic.com/58pic/15/68/85/81c58PICK34_1024.jpg" alt="" class="xsh_user_logo xsh_user_logo_radius"></a><p class="xsh_circle_label">[典型案例] <img src="/h5/images/hot.png" alt="" class="xsh_hotimg"></p><h3 class=" xsh_text_one">典型案例典型案例典型案例典型案例典型案例典型案例典型案例</h3><span class="xsh_circle_name">二级开发和</span><span class="xsh_notice_time xsh_circle_time">2016-08-15 09:25:62</span></div></li>'
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