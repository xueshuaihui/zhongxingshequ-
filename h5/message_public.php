<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>公告</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="/static/h5/css/reset.css">
    <link rel="stylesheet" href="/static/h5/css/style.css">
</head>
<body>
<?foreach ($tips as $tip){?>
<div class="xsh_noticebox">
    <a href="<?php echo encodeUrl('app.php?show=message-ptc&mid='. $tip['id']);?>">
        <p class="xsh_notice_text"><?echo $tip['subject']?></p>
        <p class="xsh_notice_text xsh_notice_time"><?echo date('Y-m-d H:i:s', $tip['starttime'])?></p>
    </a>
</div>
<?}?>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/static/h5/js/notice.js"></script>
<script src="/static/h5/js/reload.js"></script>
</html>