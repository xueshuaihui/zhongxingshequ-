<?php
require_once 'baseRepository.php';

class indexRepository extends baseRepository {
    public function getBanner() {
        $data = $this->table('common_setting')->where(['skey'=>'banner_order'])->find();
        $dataArr = explode(',', $data['svalue']);
        $map = [];
        foreach ($dataArr as $item){
            $map = array_merge($map, [$item.'title'], [$item.'jump'], [$item.'url']);
        }
        $banners = $this->table('common_setting')->in('skey', $map)->select();
        $result = [];
        foreach ($banners as $banner){
            $result[substr($banner['skey'], 0, 6)][substr($banner['skey'], 6)] = $banner['svalue'];
        }
        return array_values($result);
    }

    public function getHdzl($bkId = null) {
        if(is_null($bkId)){
            $setting = $this->table('common_setting')->where(['skey'=>'index_hdzl'])->find();
            $bkId = $setting['svalue'];
        }
        $bks = $this->table('forum_forum')->where('fup', $bkId)->whereOr('fid', $bkId)->select('fid, name');
        foreach ($bks as $k=>$bk){
            if($bk['fid'] == $bkId){
                $bks['title'] = $bk['name'];
                unset($bks[$k]);
            }
        }
        return $bks;
    }

    public function getTabs() {
        return $this->table('forum_threadtype')->fields('typeid, name')->order('displayorder')->select();
    }

    public function getInfo($typeId, $page) {
        $count = $this->table('common_setting')->where('skey', 'index_list_count')->find();
        $datas = $this->table('forum_thread')->ass('zt')->join(' LEFT JOIN zx_forum_post AS tz ON zt.tid = tz.tid')->where(['zt.sortid'=>$typeId, 'tz.first'=>1])->select();
        $valueAble = [];
        foreach ($datas as $k=>$data) {
            $valueAble[$k]['title'] = $this->subString($data['subject'], 25);
            $valueAble[$k]['tid'] = $data['tid'];
            $valueAble[$k]['description'] = $this->filterMessage($data['message']);
            $valueAble[$k]['views'] = $data['views'];
            $valueAble[$k]['date'] = date('Y-m-d', $data['dateline']);
            $valueAble[$k]['image'] = $this->getFirstImage($data['message'], $data['tid']);
        }
        return $valueAble;
    }

    private function filterMessage ($message) {
        $message = preg_replace('/\[.*?\]/', '', $message);
        $message = preg_replace('/(https|http):\/\/(.*?)(png|jpeg|gif|jpg)/i','',$message);
        return $this->subString($message, 80);
    }

    private function getFirstImage($message, $tid){
        preg_match_all('/(https|http):\/\/(.*?)(png|jpeg|gif|jpg)/i', $message, $imgUrl);
        if(isset($imgUrl[0][0])){
            return $imgUrl[0][0];
        }else{
            $attachment = $this->table('forum_threadimage')->where('tid', $tid)->find();
            return $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR.($attachment ? 'data'.DIRECTORY_SEPARATOR.'attachment'.DIRECTORY_SEPARATOR.'forum'.DIRECTORY_SEPARATOR.$attachment['attachment'] : 'static'.DIRECTORY_SEPARATOR.'zte'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'default.jpg');
        }
    }

    private function subString($string, $length){
        if(strlen($string) > $length){
            $string = mb_substr($string, 0, $length, "utf-8").'...';
        }
        return $string;
    }
}
