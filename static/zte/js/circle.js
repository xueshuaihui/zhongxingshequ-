var jq = jQuery.noConflict();
/*圈子tab*/
var circle_header = jq(".xsh_circle_header");
var tabheader = circle_header.find("ul>li>span");
var circle_tabcon = jq(".xsh_circle_tabcon");
var xsh_more = jq(".xsh_more");
var  grouptype = $(".xsh_circle_hot").attr("xid");
if(circle_tabcon.eq(0).find("ul>li").length<4){
    xsh_more.css({display:"none"})
}else{
    xsh_more.css({display:"block"})
}
tabheader.each(function(index,obj){
    jq(this).on("click",function(){
        tabheader.removeClass("xsh_circle_hot");
        jq(this).addClass("xsh_circle_hot");
        circle_tabcon.removeClass("xsh_circle_tabconnow").eq(index).addClass("xsh_circle_tabconnow");
        if(circle_tabcon.eq(index).find("ul>li").length<4){
            xsh_more.css({display:"none"})
        }else{
            xsh_more.css({display:"block"})
        }
        grouptype = jq(this).attr("xid");
    })
})
/*显示更多*/
var morecircle_mask = jq(".morecircle_mask");
var circlebox = jq(".morecircle");
function morecircle(){
    jq.ajax({
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
var morecircleMask = jq(".morecircle_mask");
function closeMask(){
    circlebox.html("");
    morecircleMask.css({display:"none"});
}
/*名片*/
var cirlis_logo = jq(".xsh_cirlis_logo");
cirlis_logo.each(function(){
    var circle_cad = jq(this).children(".xsh_circle_cad");
    jq(this).hover(function(){
        circle_cad.css({display:"block"});
    },function(){
        circle_cad.css({display:"none"});
    })
})
/**/
var circle_form = jq(".xsh_circle_form");
circle_form.find("select").change(function(){
    window.location.href = jq(this).val();
})