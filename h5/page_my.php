<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的帖子</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="/static/h5/css/reset.css">
    <link rel="stylesheet" href="/static/h5/css/style.css">
</head>
<body>
<div>
    <ul><?foreach ($lists as $list){?>
        <li class="private_letter">
            <div class="xsh_private_letter_box xsh_circle_list">
                <a href="">
                    <img src="<?echo $avatar?>" alt="" class="xsh_user_logo xsh_user_logo_radius">
                </a>
                <p class="xsh_circle_label">[<?echo $list['name']?>] <img src="static/h5/images/hot.png" alt="" class="xsh_hotimg"></p>
                <h3 class=" xsh_text_one"><?echo $list['subject']?></h3>
                <span class="xsh_circle_name"><center><?echo $user['username']?></center></span>
                <span class="xsh_notice_time xsh_circle_time"><?echo date('Y-m-d H:i:s', $list['lastpost'])?></span>
            </div>
        </li>
        <?}?>
    </ul>
</div>
</body>
</html>