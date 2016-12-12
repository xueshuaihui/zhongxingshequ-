<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>提醒</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="static/h5/css/reset.css">
    <link rel="stylesheet" href="static/h5/css/style.css">
</head>
<body>
<?foreach ($tips as $tip){?>
<div class="xsh_noticebox">
    <a href="">
        <h3 class="xsh_remind_name"><?echo '来自 '.explode(' ', $tip['message'])[0].' :'?></h3>
        <p class="xsh_notice_text xsh_remind_text"><?echo $tip['message']?></p>
        <p class="xsh_notice_text xsh_notice_time"><?echo date('Y-m-d H:i:s', $tip['dateline'])?></p>
    </a>
</div>
<?}?>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/static/h5/js/reload.js"></script>
<script src="/static/h5/js/remind.js"></script>
</html>