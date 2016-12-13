<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>圈子成员</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_management_member">
    <ul class="xsh_search_conbox xsh_circlr_member_box">
        <? foreach ($users as $user) {?>
        <li class="xsh_circle_member_list">
            <a href="<?echo encodeUrl('app.php?circle-member&uid='.$user['uid'].'&myuid='.$uid)?>">
                <div class="xsh_user_logo xsh_circlr_member">
                    <img src="<? echo $user['avatar'] ?>" alt="" class="xsh_user_logo xsh_circlr_member">
                </div>
                <p class="xsh_circle_member_name"><? echo $user['username'] ?></p>
            </a>
        </li>
        <? } ?>
    </ul>
</div>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/h5/js/touch.js"></script>
<script src="/h5/js/circle_member.js"></script>
</html>