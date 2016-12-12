/*获取圈子fid*/
var fid = window.location.href.split("?")[1].split("=")[1];
/**/
var circleinformationbox = $(".xsh_circle_information_box .xsh_private_letter_box");
circleinformationbox.on("tap",".xsh_circle_accept",function(){
    var uid = $(this).parents(".xsh_private_letter_box").attr("uid");
    console.log(uid)
    /*接受*/
    $.ajax({
        url:"http://zte.rmbplus.com/app.php?action=circle-changeUserGroupStatus",
        data:{wantPower:4,fid:fid,uid:uid},
        type:"post",
        success:function(result){
            if(result.state == 10000){
                /*添加成功*/
                window.location.href = "zxbbs://alert/"+result.msg;
                $(this).parents(".xsh_private_letter_box").remove();
            }else{
                window.location.href = "zxbbs://alert/"+result.msg;
            }
        }
    })
})
circleinformationbox.on("tap",".xsh_circle_refuse",function(){
    var uid = $(this).parents(".xsh_private_letter_box").attr("uid");
    console.log(uid)
    /*接受*/
    $.ajax({
        url:"http://zte.rmbplus.com/app.php?action=circle-ignoreApply",
        data:{fid:fid,uid:uid},
        type:"post",
        success:function(result){
            if(result.state == 10000){
                /*拒绝成功*/
                window.location.href = "zxbbs://alert/"+result.msg;
                $(this).parents(".xsh_private_letter_box").remove();
            }else{
                window.location.href = "zxbbs://alert/"+result.msg;
            }
        }
    })
})