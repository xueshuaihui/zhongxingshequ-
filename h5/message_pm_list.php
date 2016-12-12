<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>私信</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<?foreach ($list as $value){?>
<div class="private_letter">
    <div class="xsh_private_letter_box">
        <img src="<?echo $value['you']?>" alt="" class="xsh_user_logo">
        <div>
            <a href="">
                <h3 class="xsh_private_letter_name"><?echo $value['lastauthor']?></h3>
                <p class="xsh_notice_text xsh_remind_text xsh_private_letter_text"><?echo $value['message']?></p>
        </a>
        </div>
        <p class="xsh_notice_text xsh_notice_time"><?echo date('Y-m-d H:i:s',$value['lastupdate'])?></p>
    </div>
</div>
<?}?>
</body>
<script src="../static/h5/js/zepto.min.js"></script>
<script src="../static/h5/js/reload.js"></script>
<script src="../static/h5/js/private_letter.js"></script>
</html>