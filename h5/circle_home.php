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
            <a href="<?echo encodeUrl('app.php?show=member-details&uid='.$user['uid'].'&myuid='.$uid)?>">
                <div class="xsh_user_logo xsh_circlr_member">
                    <img src="<?php echo $user['avatar'] ;?>" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"><?echo cutstr($user['username'], 8)?></p>
            </a>
        </li>
        <?}?>
        <?if($profile['relation'] > 2){?>
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
        <a href="<?echo encodeUrl('app.php?show=circle-member&type=0&uid='.$uid.'&fid='.$fid)?>" class="xsh_more_member_btn">查看更多圈子成员</a>
    </div>
</div>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <a hrefs = "<?if($profile['relation'] > 2) echo 'zxbbs://circle/modifyName/'?>">
            <li class="xsh_circle_name xsh_circle_managebor xsh_revise">
                <div class="xsh_circle_information_title"><span>圈子名称</span></div>
                <div class="xsh_circle_information_con xsh_revise_text" style="margin-right:15px;"><? echo $profile['title']; ?></div>
                <?if($profile['relation'] > 2){?>
                <div class="xsh_circle_information_con xsh_more_member_btn xsh_revise_text"></div>
                <?}?>
            </li>
        </a>
        <a hrefs = "<?if($profile['relation'] > 2) echo 'zxbbs://circle/modifyDesc/'?>" >
            <li class="xsh_circle_name xsh_circle_managebor xsh_circle_synopsis xsh_revise">
                <div class="xsh_circle_information_title">
                    <span>圈子简介</span>
                    <p class="xsh_circle_syn xsh_revise_text"><? echo $profile['description']; ?></p>
                </div>
                <?if($profile['relation'] > 2){?>
                <div class="xsh_circle_information_con xsh_more_member_btn"> </div>
                <?}?>
            </li>
        </a>
    </ul>
</div>
<?if($profile['relation'] > 2 && count($wait) > 0){?>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <div class="xsh_circle_name xsh_circle_managebor">
            <div class="xsh_circle_information_title"><span>新成员申请</span></div>
        </div>
        <? foreach ($wait as $value) {?>
        <li class="xsh_circle_name xsh_circle_managebor xsh_private_letter_box" uid="<?echo $value['uid']?>">
            <div class="xsh_user_logo">
                <img src="<? echo $value['avatar'] ; ?>" alt="" class="xsh_user_logo">
            </div>
            <div class="xsh_circle_apply_box">
                <h3 class="xsh_circle_apply_name xsh_text_one"><? echo cutstr($value['username'], 8)?></h3>
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
    <? if($profile['relation'] == 5){?>
        <a href="<?echo encodeUrl('app.php?show=circle-member&type=1&uid='.$uid.'&fid='.$fid)?>">
            <div class="xsh_circle_manage_operation">
                 <span>转让圈子</span>
            </div>
        </a>
    <?}elseif($profile['relation'] > 1){ ?>
        <a href="javascript:;" class="signout">
            <div class="xsh_circle_manage_operation">
                <span>退出圈子</span>
            </div>
        </a>
    <?}elseif($profile['relation'] == 1){ ?>
        <a href="javascript:;">
            <div class="xsh_circle_manage_operation">
                <span>等待审核</span>
            </div>
        </a>
    <?} ?>
</div>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/h5/js/touch.js"></script>
<script src="/h5/js/circle_management.js"></script>
</html>