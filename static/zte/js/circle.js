/*圈子tab*/
var circle_header = $(".xsh_circle_header");
var tabheader = circle_header.find("ul>li>span");
var circle_tabcon = $(".xsh_circle_tabcon");
var xsh_more = $(".xsh_more");
var  grouptype = "join";
tabheader.each(function(index,obj){
    $(this).on("click",function(){
        tabheader.removeClass("xsh_circle_hot");
        $(this).addClass("xsh_circle_hot");
        circle_tabcon.removeClass("xsh_circle_tabconnow").eq(index).addClass("xsh_circle_tabconnow");
        if(circle_tabcon.eq(index).find("ul>li").length<4){
            xsh_more.css({display:"none"})
        }else{
            xsh_more.css({display:"block"})
        }
        if(index == 0){
            grouptype = "join";
        }else if(index == 1){
            grouptype = "manage";
        }else if(index == 2){
            grouptype = "all";
        }
    })
})
/*显示更多*/
var morecircle_mask = $(".morecircle_mask");
var circlebox = $(".morecircle");
function morecircle(){
    $.ajax({
        url:"/ztgroup.php",
        type:"post",
        data:{action:"group_list",grouptype:grouptype},
        success:function(result){
            result = JSON.parse(result);
            if(result.code == 10000){
                var str = '';
                var data = result.data;
                for(var i in data){
                    str+='<li><div class="xsh_circle_logobox"><div class="xsh_circle_logo"><a href="'+(data[i].href)+'"><img src="'+(data[i].icon)+'" alt="加载不成功"></a></div><div class="xsh_circle_card"><div class="xsh_circle_name"><a href="'+(data[i].href)+'">'+(data[i].name)+'</a></div><div class="xsh_circle_data"><div class="xsh_circle_num">成员:<span>'+(data[i].membernum)+'</span></div><div class="xsh_circle_cardnum">帖子:<span>'+(data[i].posts)+'</span></div></div></div></div></li>'
                }
                morecircle_mask.css({display:"block"});
                circlebox.html(str);
            }else{
                /*失败*/
                alert(result.error_msg);
                return;
            }
        }
    })
}
/*close mask*/
var morecircleMask = $(".morecircle_mask");
function closeMask(){
    circlebox.html("");
    morecircleMask.css({display:"none"});
}
/*名片*/
var cirlis_logo = $(".xsh_cirlis_logo");
cirlis_logo.each(function(){
    var circle_cad = $(this).children(".xsh_circle_cad");
    $(this).hover(function(){
        circle_cad.css({display:"block"});
    },function(){
        circle_cad.css({display:"none"});
    })
})
/**/
var circle_form = $(".xsh_circle_form");
circle_form.find("select").change(function(){
    circle_form.submit()
})