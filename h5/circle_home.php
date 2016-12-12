<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>圈子管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_management_member">
    <ul class="xsh_search_conbox xsh_circlr_member_box">
        <? foreach($users as $user) { ?>
        <li class="xsh_circle_member_list">
            <a href="javascript:;">
                <div class="xsh_user_logo xsh_circlr_member">
                    <img src="<?php echo $user['avatar'] ;?>" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"><?echo $user['username'] ;?></p>
            </a>
        </li>
        <?}?>
        <li class="xsh_circle_member_list">
            <a href="javascript:;">
                <div class="xsh_user_logo xsh_circlr_member xsh_circlr_member_btn">
                    <img src="../static/h5/images/add.png" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
            </a>
        </li>
        <li class="xsh_circle_member_list">
            <a href="javascript:;">
                <div class="xsh_user_logo xsh_circlr_member xsh_circlr_member_btn">
                    <img src="/static/h5/images/jian.png" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
            </a>
        </li>
    </ul>
</div>
<div class="xsh_more_member_box">
    <div class="xsh_more_member">
        <a href="" class="xsh_more_member_btn">查看更多圈子成员</a>
    </div>
</div>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <a href="javascript:;">
            <li class="xsh_circle_name">
                <div class="xsh_circle_information_title"><span>圈子名称</span></div>
                <div class="xsh_circle_information_con xsh_more_member_btn"><? echo $profile['title']; ?></div>
            </li>
        </a>
        <a href="javascript:;">
            <li class="xsh_circle_name xsh_circle_synopsis">
                <div class="xsh_circle_information_title">
                    <span>圈子简介</span>
                    <p class="xsh_circle_syn"><? echo $profile['description']; ?></p>
                </div>
                <div class="xsh_circle_information_con xsh_more_member_btn"> </div>
            </li>
        </a>
    </ul>
</div>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <div class="xsh_circle_name">
            <div class="xsh_circle_information_title"><span>新成员申请</span></div>
        </div>
        <? foreach ($wait as $value) {?>
        <li class="xsh_circle_name xsh_private_letter_box">
            <div class="xsh_user_logo">
                <img src="<? echo $value['avatar'] ; ?>" alt="" class="xsh_user_logo">
            </div>
            <div class="xsh_circle_apply_box">
                <h3 class="xsh_circle_apply_name xsh_text_one"><? echo $value['username'] ; ?></h3>
                <div>
                    <p class="xsh_text_one xsh_circle_apply_syn"><? echo $value['bo'] ; ?></p>
                </div>
            </div>
            <div class="xsh_circle_apply_btnbox">
                <div class="xsh_circle_apply_btn xsh_circle_accept">
                    <span>接受</span>
                </div>
                <div class="xsh_circle_apply_btn xsh_circle_refuse">
                    <sapn>拒绝</sapn>
                </div>
            </div>
        </li>
        <? } ?>
    </ul>
</div>
<div class="xsh_circle_manage">
    <a href="">
        <div class="xsh_circle_manage_operation">
            <? if($profile['relation'] == 4){
                echo '<span>转让圈子</span>';
            }elseif($profile['relation'] != 0){
                echo '<span>退出圈子</span>';
            }else{
                echo '<span>加入圈子</span>';
            } ?>
        </div>
    </a>
</div>
</body>
</html>