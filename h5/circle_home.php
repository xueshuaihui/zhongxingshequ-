<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>圈子管理(<?echo $usersCount[0]?>)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_management_member">
    <ul class="xsh_search_conbox xsh_circlr_member_box">
        <? foreach($users as $user) { ?>
        <li class="xsh_circle_member_list">
            <a href="<?echo encodeUrl('app.php?circle-member&uid='.$user['uid'].'&myuid='.$uid)?>">
                <div class="xsh_user_logo xsh_circlr_member">
                    <img src="<?php echo $user['avatar'] ;?>" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"><?echo $user['username'] ;?></p>
            </a>
        </li>
        <?}?>
        <?if($profile['relation'] == 2){?>
        <li class="xsh_circle_member_list">
            <a href="zxbbs://circle/invite">
                <div class="xsh_user_logo xsh_circlr_member xsh_circlr_member_btn">
                    <img src="../static/h5/images/add.png" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"></p>
            </a>
        </li>
        <li class="xsh_circle_member_list">
            <a href="zxbbs://circle/defriend">
                <div class="xsh_user_logo xsh_circlr_member xsh_circlr_member_btn">
                    <img src="/static/h5/images/jian.png" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"></p>
            </a>
        </li>
        <?}?>
    </ul>
</div>
<div class="xsh_more_member_box">
    <div class="xsh_more_member">
        <a href="<?echo encodeUrl('app.php?show=circle-member&fid='.$fid)?>" class="xsh_more_member_btn">查看更多圈子成员</a>
    </div>
</div>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <a href="zxbbs://circle/modifyName">
            <li class="xsh_circle_name xsh_circle_managebor">
                <div class="xsh_circle_information_title"><span>圈子名称</span></div>
                <div class="xsh_circle_information_con xsh_more_member_btn"><? echo $profile['title']; ?></div>
            </li>
        </a>
        <a href="zxbbs://circle/modifyDesc">
            <li class="xsh_circle_name xsh_circle_managebor xsh_circle_synopsis">
                <div class="xsh_circle_information_title">
                    <span>圈子简介</span>
                    <p class="xsh_circle_syn"><? echo $profile['description']; ?></p>
                </div>
                <div class="xsh_circle_information_con xsh_more_member_btn"> </div>
            </li>
        </a>
    </ul>
</div>
<?if($profile['relation'] == 2 && count($wait) > 0){?>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <div class="xsh_circle_name xsh_circle_managebor">
            <div class="xsh_circle_information_title"><span>新成员申请</span></div>
        </div>
        <? foreach ($wait as $value) {?>
        <li class="xsh_circle_name xsh_circle_managebor xsh_private_letter_box" uid="">
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
<?}?>
<div class="xsh_circle_manage">
    <? if($profile['relation'] == 4){?>
        <a href="zxbbs://circle/transfer">
            <div class="xsh_circle_manage_operation">
                 <span>转让圈子</span>
            </div>
        </a>
    <?}elseif($profile['relation'] != 0){ ?>
        <a href="javascript:;">
            <div class="xsh_circle_manage_operation">
                <span>退出圈子</span>
            </div>
        </a>
    <?}else{?>
        <a href="">
            <div class="xsh_circle_manage_operation">
                <span>加入圈子</span>
            </div>
        </a>
    <?} ?>
</div>
</body>
</html>