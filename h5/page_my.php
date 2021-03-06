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
    <ul>
        <?foreach ($lists as $item){?>
            <li class="private_letter">
                <div class="xsh_private_letter_box xsh_circle_list">
                    <a href="<?echo encodeUrl("app.php?show=member-details&uid={$item['authorid']}&myuid={$uid}")?>">
                        <img src="<?echo $item['icon'];?>" alt="" class="xsh_user_logo xsh_user_logo_radius">
                    </a>
                    <p class="xsh_circle_label">[<?echo $item['name'];?>]
                        <?if($item['stamp'] > -1){
                            switch ($item['stamp']){
                                case '0' : $img = 'jh@2x.png';break;
                                case '1' : $img = 'rt@2x.png';break;
                                case '2' : $img = 'mt@2x.png';break;
                                case '3' : $img = 'yx@2x.png';break;
                                case '4' : $img = 'zd@2x.png';break;
                                case '5' : $img = 'tj@2x.png';break;
                                case '6' : $img = 'yc@2x.png';break;
                                case '7' : $img = 'bztj@2x.png';break;
                                case '8' : $img = 'bl@2x.png';break;
                                case '19' : $img = 'bj@2x.png';break;
                                default : $img = '';break;
                            }
                            ?>
                            <img src="../static/h5/images/<?echo $img?>" alt="" class="xsh_hotimg">
                        <?}?>
                        <?if($item['displayorder']){?>
                            <img src="../static/h5/images/zd@2x.png" alt="" class="xsh_hotimg">
                        <?}?>
                    </p>
                    <a href="<?echo encodeUrl("app.php?show=page-pageContent&fid={$item['fid']}&tid={$item['tid']}&uid={$uid}")?>">
                        <h3 class=" xsh_text_one"><?echo $item['subject']?></h3>
                    </a>
                    <span class="xsh_circle_name"><center><?echo $item['author']?></center></span>
                    <span class="xsh_notice_time xsh_circle_time"><?echo date('Y-m-d H:i:m', $item['lastpost'])?></span>
                </div>
            </li>
        <?}?>
    </ul>
</div>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/h5/js/circle_of_details.js"></script>
</html>