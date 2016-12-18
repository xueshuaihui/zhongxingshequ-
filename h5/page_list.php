<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>圈子详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="../static/h5/css/reset.css">
    <link rel="stylesheet" href="../static/h5/css/style.css">
</head>
<body>
<div class="xsh_search_box" id="wrapper">
    <div class="xsh_search_conbox">
        <laber for="xsh_search" class="xsh_search"><a class="xsh_searchimg"><img src="../static/h5/images/search.png" alt=""></a><input type="text" id="xsh_search" placeholder="请输入要查询的关键字"></laber>
        <div class="xsh_post"><a href="zxbbs://post/new/<?echo $fid?>">我要发帖</a></div>
    </div>
</div>
<div class="xsh_circle_list_box">
    <ul>
        <?foreach ($list as $item){?>
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
                    <img src="../static/h5/images/zhiding2.png" alt="" class="xsh_hotimg">
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
<div class="xsh_asynchronous_searchbox"></div>
</body>
<script src="/static/h5/js/zepto.min.js"></script>
<script src="/h5/js/circle_of_details.js"></script>
<script>
    var u = navigator.userAgent;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(isiOS){
        var xsh_search = $("#xsh_search");
        xsh_search.on("focusin",function(){
            $(".xsh_search_box").css({position:"absolute",top:$(window).scrollTop(),left:"0"});
            alert("absolute")
        })
        window.onscroll = function(){
        xsh_search.blur();
        $(".xsh_search_box").css({position:"fixed",top:"0",left:"0"});
        }
        xsh_search.on("blur",function(){
             $(".xsh_search_box").css({position:"fixed",top:"0",left:"0"});
        })

    }
</script>
</html>