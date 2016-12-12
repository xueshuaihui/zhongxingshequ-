<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>信息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="static/h5/css/reset.css">
    <link rel="stylesheet" href="static/h5/css/style.css">
</head>
<body>
<div class="xsh_message_box">
    <ul>
        <?foreach ($lists as $list){ if($list['position'] == 'l'){?>
                <li class="xsh_you">
                    <div class="xsh_message_logobox">
                        <a href="">
                            <img src="<?echo $list['you']?>" alt="">
                        </a>
                    </div>
                    <div class="xsh_message_text">
                        <div class="xsh_you_trigon"></div>
                        <p><b><?echo $list['message']?></b></p>
                    </div>
                </li>
            <?}else{?>
                <li class="xsh_me">
                    <div class="xsh_message_logobox">
                        <a href="">
                            <img src="<?echo $list['me']?>" alt="">
                        </a>
                    </div>
                    <div class="xsh_message_text">
                        <div class="xsh_me_trigon"></div>
                        <p><b><?echo $list['message']?></b></p>
                    </div>
                </li>
            <?}}?>
        <a name="xsh_foot"></a>
    </ul>
    <div class="xsh_message_fixed">
        <div class="xsh_message_formbox">
            <form action="">
                <input type="text" class="xsh_message_inputext" required>
                <a href="javascript:;" class="xsh_message_submit">发送</a>
            </form>
        </div>
    </div>
</div>
</body>
<script src="h5/js/zepto.min.js"></script>
<script src="h5/js/touch.js"></script>
<script src="h5/js/message.js"></script>
</html>