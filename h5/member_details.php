<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><? echo $userProfile['username'];?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box">
        <li class="xsh_circle_name xsh_private_letter_box xsh_information_name">
            <div class="xsh_user_logo">
                <img src="<? echo $avatar; ?>" alt="" class="xsh_user_logo">
            </div>
            <h3 class="xsh_circle_apply_name xsh_text_one"><? echo $userProfile['username'];?></h3>
            <div>
                <p class="xsh_text_one xsh_circle_apply_syn xsh_user_information"><span>个性签名：</span><? echo $userProfile['bio'] ?:'<b style="color:#ccc">这个人很懒，什么都没写</b>';?></p>
            </div>
        </li>
    </ul>
</div>
<div class="xsh_circle_information">
    <ul class="xsh_circle_information_box xsh_information_box">
        <li><span class="xsh_information_title">用户组:</span><span><? echo $userGroup['grouptitle']; ?></span></li>
        <li><span class="xsh_information_title">好友数:</span><span><? echo $userCount['friends']; ?></span></li>
        <li><span class="xsh_information_title">回帖数:</span><span><? echo $userCount['posts']; ?></span></li>
        <li><span class="xsh_information_title">主题数:</span><span><? echo $userCount['threads']; ?></span></li>
    </ul>
</div>
<div class="xsh_circle_manage xsh_information_btn_box">
    <?if($relation == 1){?>
    <a href="<?echo encodeUrl('app.php?show=message_pmc&')?>">
        <div class="xsh_circle_manage_operation xsh_information_btn"><span>发消息</span></div>
    </a>
    <?}elseif($relation == 0){?>
    <div class="xsh_circle_manage_operation xsh_information_btn xsh_information_min_btn"><span>加为好友</span></div>
    <div class="xsh_circle_manage_operation xsh_information_btn xsh_information_min_btn"><span>发消息</span></div>
    <?}?>
</div>
</body>
</html>