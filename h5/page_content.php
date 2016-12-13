<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>帖子详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_postdetails_box">
    <div class="xsh_postdetails_header">
        <h3 class=""><?echo $result[0]['subject'];?></h3>
    </div>
    <div class="xsh_private_letter_box xsh_post_user_box">
        <a href="">
            <img src="<?echo $result[0]['usericon']?>" alt="" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo">
            <p><?echo $result[0]['author']?></p>
            <p><?echo date('Y-m-d H:i:s', $result[0]['dateline'])?></p>
        </a>
    </div>
</div>
<div class="xsh_postdetails_box xsh_postdetails_textbox">
    <div class="xsh_postdetails_header xsh_postdetails_text">
        <p><?echo $result[0]['message']?></p>
    </div>
    <ul class="xsh_postdetails_images">
<!--        <li><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></li>-->
<!--        <li><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></li>-->
    </ul>
</div>
<div class="xsh_floor_box">
    <ul>
        <?foreach ($result as $k=>$value){ if($k == 0) continue;?>
        <li class="xsh_floor">
            <a href=""><img src="<?echo $value['usericon']?>" alt="" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a>
            <p><span class="xsh_floor_username"><?echo $value['author']?>：</span><span class="xsh_floor_number"><?echo $k?>楼</span></p>
            <div class="xsh_floor_textbox">
                <p class="xsh_floor_text"><?echo $value['message']?></p>
                <ul>
                    <li class="xsh_floor_text_img"><a href=""><img src="http://i0.sinaimg.cn/gm/j/i/2009-03-17/U1850P115T41D162082F756DT20090317125249.jpg" alt=""></a></li>
                </ul>
                <span class="xsh_floor_text_time"><?echo date('Y-m-d H:i:s', $value['dateline'])?></span>
            </div>
        </li>
        <?}?>
    </ul>
</div>
<!---->
<div class="xsh_message_fixed">
    <div class="xsh_message_formbox">
        <form action="">
            <input type="text" class="xsh_message_inputext" disabled>
            <a href="javascript:;" class="xsh_message_submit">发送</a>
        </form>
    </div>
</div>
</body>
</html>