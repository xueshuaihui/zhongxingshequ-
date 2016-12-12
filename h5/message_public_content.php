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
<div class="xsh_noticebox xsh_notice_conbox">
    <div class="xsh_private_letter_box">
        <h3 class="xsh_private_letter_name"><?echo $tip['subject']?></h3>
        <p class="xsh_notice_text xsh_notice_time"><?echo date('Y-m-d H:i:s', $tip['starttime'])?></p>
        <p class="xsh_remind_text"><?echo $tip['message']?></p>
    </div>
</div>
</body>
</html>