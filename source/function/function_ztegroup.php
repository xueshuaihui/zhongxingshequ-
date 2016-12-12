<?php

function pageFormat($url, $count, $cur = 1) {
    if($count < 2){
        return '';
    }
    $result = '';
    if($cur > 1) {
        $result .= '<a href="'.bind_page_url($url, $cur-1).'"><li onclick="asyncLoad('.($cur-1).')" class="xsh_pages_Previous"></li></a>';
    }
    if($count >= 10 && $cur > 4){
        $result .= '<li>1</li>';
        if($cur > 5){
            $result .= '<li>...</li>';
        }
    }
    if($cur > 4 && $count > 10 && ($count - $cur > 6)){
        $page = $cur-3;
    }elseif($count > 10 && ($count - $cur < 7) ){
        $page = $count-9;
    }else{
        $page = 1;
    }
    if($count > 10 && $count - $cur < 7) {
        $range = 10;
    }else{
        $range = (min(10, $count));
    }
    for ($i = 1; $i <= $range; $i++) {
        
        if($page == $cur) {
            $result .= '<a href="'.bind_page_url($url, $page).'"><li class="xsh_pages_hot">'.$page.'</li></a>';
        }else{
            $result .= '<a href="'.bind_page_url($url, $page).'"><li>'.$page.'</li></a>';
        }
        $page++;
    }
    if($count > 10 && ($count - $cur) > 6){
        if(($count - $cur) > 7){
            $result .= '<li>...</li>';
        }
        $result .= '<li>'.$count.'</li>';
    }
    if($cur < $count) {
        $result .= '<a href="'.bind_page_url($url, $cur+1).'"><li  class="xsh_pages_next"></li></a>';
    }
    return $result;
}

function bind_page_url($url, $page) {
    $link_char = '';
    if (strpos($url, "?") !== false) {
        $link_char = '&';
    } else {
        $link_char = "?";
    }
    
    return $url.$link_char.'page='.$page;
}





