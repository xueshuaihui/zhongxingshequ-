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
        $bks = $this->table('forum_forum')->ass('f')->join(' LEFT JOIN '.$this->prefix.'forum_forumfield AS o ON f.fid = o.fid')->where('f.fup', $bkId)->whereOr('f.fid', $bkId)->select();
        $result = [];
        $bkDatas = [];
        foreach ($bks as $k=>$bk){
            if($bk['fid'] == $bkId){
                $result['title'] = $bk['name'];
            }else{
                $bkDatas[$k]['fid'] = $bk['fid'];
                $bkDatas[$k]['icon'] = strpos($bk['icon'], 'http') === false ? BASEURL.__.'data'.__.'attachment'.__.'common'.__.$bk['icon'] : $bk['icon'];
                $bkDatas[$k]['name'] = $bk['name'];
            }
        }
        $result['bks'] = array_values($bkDatas);
        return $result;
    }

    public function getTabs() {
        return $this->table('forum_threadtype')->fields('typeid, name')->order('displayorder')->select();
    }

    public function getInfo($typeId, $page = 1, $count = 10) {
        $count = $this->table('common_setting')->where('skey', 'index_list_count')->find();
        $start = ($page - 1) * $count;
        $datas = $this->table('forum_thread')->ass('zt')->join(' LEFT JOIN '.$this->prefix.'forum_post AS tz ON zt.tid = tz.tid')->where(['zt.sortid'=>$typeId, 'tz.first'=>1])->limit($start.','.$count)->select();
        $valueAble = [];
        foreach ($datas as $k=>$data) {
            $valueAble[$k]['title'] = $this->subString($data['subject'], 25);
            $valueAble[$k]['fid'] = $data['fid'];
            $valueAble[$k]['tid'] = $data['tid'];
            $valueAble[$k]['description'] = $this->filterMessage($data['message']);
            $valueAble[$k]['views'] = $data['views'];
            $valueAble[$k]['date'] = date('Y-m-d', $data['dateline']);
            $valueAble[$k]['image'] = $this->getFirstImage($data['message'], $data['tid']);
        }
        return $valueAble;
    }

    public function pushQuestion($uid, $bkId, $content) {
        require_once ROOT."source".__.'function'.__.'function_forum.php';
        $user = $this->getUserByUid($uid);
        $newthread = array(
            'fid' => $bkId,
            'posttableid' => 0,
            'readperm' => 0,
            'sortid' => 0,
            'author' => $user['username'],
            'authorid' => $uid,
            'subject' => '专家提问：',
            'dateline' => getglobal('timestamp'),
            'lastpost' => getglobal('timestamp'),
            'lastposter' => $user['username'],
            'status' => 32
        );
        //主题
        $ztid = $this->table('forum_thread')->store($newthread);
        $data = array(
            'fid' => $bkId,
            'first' => '1',
            'tid' => $ztid,
            'author' => $user['username'],
            'authorid' => $uid,
            'subject' => '专家提问：',
            'dateline' => getglobal('timestamp'),
            'message' => $content,
            'useip' => getglobal('clientip'),
            'port' => getglobal('remoteport'),
        );
        //帖子
        $pid = insertpost($data);
        if($pid){
            $banzhuIds = $this->table('forum_moderator')->where('fid', $bkId)->select();
            $this->sendMessageToIds($banzhuIds, $uid, $user['username'], $ztid, $bkId, $pid);
        }
        return true;
    }

    private function sendMessageToIds ($ids, $authorid, $author, $zt, $lt, $tz) {
        if (is_numeric($ids)) {
            $ids = array($ids);
        }
        foreach ($ids as $id) {
            if (!is_numeric($id)) {
                $id = $id['uid'];
            }
            notification_add($id, 'post', '<a href="home.php?mod=space&uid=' . $authorid . '">' . $author . '</a> 向您提出了问题 <a href="forum.php?mod=redirect&goto=findpost&tid=' . $zt . '&pid=' . $tz . '" target="_blank" class="lit">点击查看详情</a>', array(
                'tid' => $zt, //主题ID
                'subject' => '',//标题
                'fid' => $lt,//论坛ID
                'pid' => $tz,//帖子ID
                'from_id' => $zt,//主题ID
                'from_idtype' => 'post',
            ));
        }
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
            return $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://'.$_SERVER['HTTP_HOST'].__.($attachment ? 'data'.__.'attachment'.__.'forum'.__.$attachment['attachment'] : 'static'.__.'zte'.__.'images'.__.'default.jpg');
        }
    }

    private function subString($string, $length){
        return cutstr($string, $length);
    }
}
