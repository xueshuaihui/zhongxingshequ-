var jq = jQuery.noConflict();
var xsh_favatar = $(".xsh_favatar");
jq(".authicn").hover(function(){
    console.log(jq(this))
    jq(this).parents(".plc").children(".xsh_favatar").css({display:"block"});
},function(){
    jq(this).parents(".plc").children(".xsh_favatar").css({display:"none"});
})