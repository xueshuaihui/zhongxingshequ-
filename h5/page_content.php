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
        <a href="<?echo encodeUrl('app.php?show=member-details&uid='.$result[0]['authorid']);?>">
            <img src="<?echo $result[0]['usericon']?>" alt="" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo">
            <p><?echo cutstr($result[0]['author'], 8)?></p>
            <p><?echo date('Y-m-d H:i:s', $result[0]['dateline'])?></p>
        </a>
    </div>
</div>
<div class="xsh_postdetails_box xsh_postdetails_textbox">
    <div class="xsh_postdetails_header xsh_postdetails_text">
        <p><?echo $result[0]['message']?></p>
    </div>
    <ul class="xsh_postdetails_images">
        <?if($result[0]['attach']){?>
        <?foreach ($result[0]['attach'] as $item){ if($item['isimage']){?>
                <li><img src="<?echo $item['attachment']?>" alt=""></li>
        <?}}}?>
        <?if($result[0]['img']){?>
            <?foreach ($result[0]['img'] as $item){?>
                <li><img src="<?echo $item?>" alt=""></li>
            <?}}?>
    </ul>
</div>
<div class="xsh_floor_box">
    <ul>
        <?foreach ($result as $k=>$value){ if($k == 0) continue;?>
        <li class="xsh_floor" pid="<?echo $value['pid']?>">
            <a href="<?echo encodeUrl('app.php?show=member-details&uid='.$value['authorid']);?>"><img src="<?echo $value['usericon']?>" alt="" class="xsh_user_logo xsh_user_logo_radius xsh_post_user_logo"></a>
            <p><span class="xsh_floor_username"><?echo $value['author']?>：</span><span class="xsh_floor_number"><?echo $k?>楼</span></p>
            <div class="xsh_floor_textbox">
            <?if($value['reply'] != ''){ $reply = explode('/\n', $value['reply']);?>
                <div class="reply">
                    <p><?echo $reply[0]?></p>
                    <p><?echo $reply[1]?></p>
                </div>
                <?}?>
                <p class="xsh_floor_text"><?echo $value['message']?></p>
                <ul><?$count = count($value['attach']);?>
                    <? if($count){?>
                        <?foreach ($value['attach'] as $item){?>
                        <?if($count == 1 && $item['isimage']){?>
                            <li class="xsh_floor_text_img xsh_floor_text_img_one"><a href="javascript:;"><img src="<?echo $item['attachment'] ?>" alt=""></a></li>
                        <?}elseif($count ==2 || $count == 4 && $item['isimage']){?>
                            <li class="xsh_floor_text_img xsh_floor_text_img_two"><a href="javascript:;"><img src="<?echo $item['attachment'] ?>" alt=""></a></li>
                        <?}elseif($item['isimage']){?>
                            <li class="xsh_floor_text_img"><a href="javascript:;"><img src="<?echo $item['attachment'] ?>" alt=""></a></li>
                        <?}}}?>
                </ul>
                <span class="xsh_floor_text_time"><?echo date('Y-m-d H:i:s', $value['dateline'])?></span>
            </div>
        </li>
        <?}?>
    </ul>
    <div class="reloadbox">
            <div class="reloadimg">
                <img src="/static/h5/images/reload.png" alt="">
            </div>
    </div>
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
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                        <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div>
            </div>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/h5/js/touch.js"></script>
<link rel="stylesheet" href="/h5/swipe/photoswipe.css">
<link rel="stylesheet" href="/h5/swipe/default-skin/default-skin.css">
<script src="/h5/swipe/photoswipe-ui-default.min.js"></script>
<script src="/h5/swipe/photoswipe.min.js"></script>
<script src="/h5/js/page_content.js"></script>
</html>