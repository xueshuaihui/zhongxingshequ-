<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>圈子详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="static/h5/css/reset.css">
    <link rel="stylesheet" href="static/h5/css/style.css">
</head>
<body>
<div class="xsh_search_box">
    <div class="xsh_search_conbox">
        <laber for="xsh_search" class="xsh_search"><a class="xsh_searchimg"><img src="static/h5/images/search.png" alt=""></a><input type="text" id="xsh_search" placeholder="请输入要查询的关键字"></laber>
        <div class="xsh_post"><a href="发帖UEL">我要发帖</a></div>
    </div>
</div>
<div>
    <ul>
        <?foreach ($list as $item){?>
        <li class="private_letter">
            <div class="xsh_private_letter_box xsh_circle_list">
                <a href="">
                    <img src="<?echo $item['icon'];?>" alt="" class="xsh_user_logo xsh_user_logo_radius">
                </a>
                <p class="xsh_circle_label">[<?echo $item['name'];?>] <img src="static/h5/images/hot.png" alt="" class="xsh_hotimg"></p>
                <h3 class=" xsh_text_one"><?echo $item['subject']?></h3>
                <span class="xsh_circle_name"><center><?echo $item['author']?></center></span>
                <span class="xsh_notice_time xsh_circle_time"><?echo date('Y-m-d H:i:m', $item['lastpost'])?></span>
            </div>
        </li>
        <?}?>
    </ul>
</div>
</body>
</html>