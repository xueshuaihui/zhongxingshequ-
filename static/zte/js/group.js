var jq = jQuery.noConflict();
jq(".icn").hover(function(){
    jq(this).children(".xsh_user_groupdata").css({display:"block"});
},function(){
    jq(this).children(".xsh_user_groupdata").css({display:"none"});
})