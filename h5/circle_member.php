<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>圈子成员(<?echo $usersCount?>)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_management_member">
    <ul class="xsh_search_conbox xsh_circlr_member_box">
        <? foreach ($users as $user) {?>
        <li class="xsh_circle_member_list">
            <a href="<?if(!$type) echo encodeUrl('app.php?show=member-details&uid='.$user['uid']);?>" id="<?echo $user['uid']?>" class="xsh_circle_member_one">
                <div class="xsh_user_logo xsh_circlr_member">
                    <img src="<? echo $user['avatar'] ?>" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"><?echo cutstr($user['username'], 8) ?></p>
            </a>
        </li>
        <? } ?>
        <?if($profile['relation'] > 2){?>
            <li class="xsh_circle_member_list">
                <a href="zxbbs://circle/invite/<?echo $fid?>">
                    <div class="xsh_user_logo xsh_circlr_member xsh_circlr_member_btn">
                        <img src="../static/h5/images/add.png" alt="" class="xsh_user_logo xsh_circlr_member">
                    </div>
                    <p class="xsh_circle_member_name"></p>
                </a>
            </li>
            <li class="xsh_circle_member_list">
                <a href="zxbbs://circle/defriend/<?echo $fid?>">
                    <div class="xsh_user_logo xsh_circlr_member xsh_circlr_member_btn">
                        <img src="/static/h5/images/jian.png" alt="" class="xsh_user_logo xsh_circlr_member">
                    </div>
                    <p class="xsh_circle_member_name"></p>
                </a>
            </li>
        <?}?>
    </ul>
</div>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/h5/js/touch.js"></script>
<?if($type){?>
<script src="/h5/js/circle_member.js"></script>
<?}?>
</html>