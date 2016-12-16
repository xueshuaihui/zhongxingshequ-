<?php
require_once MODEL.'mdbModel.php';

class baseRepository {
    protected $prefix = 'zx_';
    protected function table($table = false){
        if(!$table){
            return mdbModel::baseModel();
        }else{
            return mdbModel::model($table);
        }
    }

    protected function randNum ($size = 6, $type = 2) {
        $result = '';
        $randString2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $randString1 = '0123456789';
        $param = 'randString'.$type;
        for($i = 0; $i < $size; $i++){
            $result .= substr($$param, rand(0, strlen($$param) - 1 ), 1);
        }
        return $result;
    }

    public function blank() {
        return $this->table();
    }

    public function getUserByUid($uid, $form = 'common_member') {
        return $this->table($form)->where('uid', $uid)->find();
    }

    public function getUserProfile($where = []) {
        return $this->table('common_member_profile')->where($where)->find();
    }

    public function getUserByUsername($username, $from = 'ucenter_members') {
        return $this->table($from)->where('username', $username)->find();
    }

    public function getAvatar($uid, $size = 'small') {
        return avatar($uid, $size, true);
    }

    public function getAttach($attachId) {
        $index = $this->table('forum_attachment')->in('aid', $attachId)->select('aid, tableid');
        foreach ($index as $k=>$value){
            $a = $this->table('forum_attachment_'.$value['tableid'])->where('aid', $value['aid'])->find('filename, isimage, attachment, remote');
            if(!$a['remote']){
                $a['attachment'] = BASEURL.__.'data'.__.'attachment'.__.'forum'.__.$a['attachment'];
            }
            unset($a['remote']);
            $index[$k] = $a;
        }
        return $index;
    }

    public function getUserCount($uid) {
        return $this->table('common_member_count')->where('uid', $uid)->find();
    }

    public function getUserGroup($groupid) {
        return $this->table('common_usergroup')->where('groupid', $groupid)->find();
    }
    public function sendMessage($ids, $type, $note, $notevars = array()) {
        if(is_string($ids) || is_numeric($ids)){
            $ids = array($ids);
        }
        foreach ($ids as $id){
            notification_add($id, $type, $note, $notevars, $notevars);
        }
    }

    public function getFriendList($uid) {
        return $this->table('home_friend')
                    ->ass('f')
                    ->join(' LEFT JOIN '.$this->prefix.'common_member AS u ON f.fuid = u.uid')
                    ->join(' LEFT JOIN '.$this->prefix.'common_member_profile AS p ON f.fuid = p.uid')
                    ->where('f.uid', $uid)
                    ->select('u.uid, u.username, p.bio');
    }

    public function isFriend($uid, $theUid) {
        return $this->table('home_friend')
            ->ass('f')
            ->join(' LEFT JOIN '.$this->prefix.'common_member AS u ON f.fuid = u.uid')
            ->join(' LEFT JOIN '.$this->prefix.'common_member_profile AS p ON f.fuid = p.uid')
            ->where(['f.uid'=>$uid, 'f.fuid'=>$theUid])
            ->select('u.uid, u.username, p.bio');
    }

    public function countGroupUser($fid) {
        return $this->table('forum_groupuser')->where('fid', $fid)->whereWhere('level', '>', 0)->find('COUNT(*)');
    }

    public function getGroupUser($fid, $field, $page, $count = 10, $level = 1, $forceall = false) {
        $data = $this->table('forum_groupuser')->where(['fid'=>$fid]);
        if(!$forceall && $level || $level === 0){
            $data->whereWhere('level', '=', $level);
        }elseif(!$forceall){
            $data->whereWhere('level', '>', 0);
        }
        if($page){
            $start = ($page - 1) * $count;
            $data->limit($start.','.$count);
        }
        return $data->order('joindateline')->select($field);
    }

    public function addBlindTag($id, $tags, $idtype) {
        foreach ($tags as $tag){
            $res = $this->table('common_tagitem')->store([
                'tagid' => $tag,
                'itemid'=>$id,
                'idtype'=>$idtype
            ], false);
            if(!$res){
                return false;
            }
        }
        return true;
    }

    public function saveAttachmentIndex($attacements, $uid) {
        foreach ($attacements as $k=>$attacement){
            $aid = $this->table('forum_attachment')->store([
                'tid' => 0,
                'pid' => 0,
                'uid' => $uid,
                'tableid' => 0,
            ], true);
            $attacements[$k]['aid'] = $aid;
        }
        return $attacements;
    }

    public function saveAttachment($attacements, $pid, $tid, $uid) {
        foreach ($attacements as $attacement){
            $attacheTableId = $tid%10;
            $this->table('forum_attachment')->where('aid', $attacement['aid'])->update(['tid'=>$tid, 'pid'=>$pid, 'tableid'=>$attacheTableId]);
            $this->table('forum_attachment_'.$attacheTableId)->store([
                'aid'=>$attacement['aid'],
                'tid'=>$tid,
                'pid'=>$pid,
                'uid'=>$uid,
                'dateline'=>getglobal('timestamp'),
                'filename'=>$attacement['filename'],
                'filesize'=>$attacement['filesize'],
                'attachment'=>$attacement['attachment'],
                'description' => '',
                'isimage' => 1,
                'width' => $attacement['width']
            ]);
        }
    }

    public function uploadImages($images, $path) {

        $result = [];
        foreach ($images as $image){
            $res = $this->uploadImage($image, $path);
            if(!is_array($res)){
                return $res;
            }
            $result[] = $res;
        }
        return $result;
    }

    public function uploadAvatar($image, $uid) {
        //判断错误
        if($image['error'] > 0){
            return 10018;
        }
        //判断格式
        if(!in_array($image['type'], ['image/jpg','image/jpeg'])){
            return 10017;
        }
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $file1 = 'uc_server/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_avatar_big.jpg';
        $file2 = 'uc_server/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_avatar_middle.jpg';
        $file3 = 'uc_server/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_avatar_small.jpg';
        self::cutAvatar($image['tmp_name'], $file1, 'b');
        self::cutAvatar($image['tmp_name'], $file2, 'm');
        self::cutAvatar($image['tmp_name'], $file3, 's');
        return true;
    }

    private function uploadImage($image, $path){
        $afterFixArr = ['image/jpg'=>'jpg','image/jpeg'=>'jpeg'];
        //判断错误
        if($image['error'] > 0){
            return 10018;
        }
        //判断格式
        $after = explode('.', $image['name']);
        if(!in_array($after[count($after) - 1], ['jpg','jpeg'])){
            return 10017;
        }
        //直接从缓存中获取，并截取
        $imgName = uniqid($this->randNum(6));
        $afterFix = '.'.$after;//$afterFixArr[$image['type']];
        $basePath = ROOT.'data'.__.'attachment'.__.$path.__;
        $filePath1 = date('Ym', time());
        $filePath2 = date('d', time());
        $filePath = $filePath1.__.$filePath2;
        $sourceImg = $image['tmp_name'];
        $fileAttachment = $basePath.$filePath.__.$imgName.$afterFix;
        if(!file_exists($basePath)){
            mkdir($basePath);
        }
        if(!file_exists($basePath.$filePath1)){
            mkdir($basePath.$filePath1);
        }
        if(!file_exists($basePath.$filePath1.__.$filePath2)){
            mkdir($basePath.$filePath1.__.$filePath2);
        }
        move_uploaded_file($sourceImg, $fileAttachment);
        $width = self::cutImg($fileAttachment, '', '', true);
        self::cutImg($fileAttachment, $fileAttachment.'.s'.$afterFix, 's');
        self::cutImg($fileAttachment, $fileAttachment.'.m'.$afterFix, 'm');
        return ['filename'=>$image['name'], 'filesize'=>$image['size'], 'attachment'=>$filePath.__.$imgName.$afterFix, 'width'=>$width];
    }

    private static function cutAvatar($source, $newName, $size = 'b'){
        list($source_width, $source_height)=getimagesize($source);//获取原图片高度、宽度
        if($size == 'b'){
            $dst_w = $dst_h = 200;
        }elseif($size == 'm'){
            $dst_w = $dst_h = 120;
        }else{
            $dst_w = $dst_h = 48;
        }
        if ($source_width < $source_height) {
            $src_x = 0;//设定载入图片要载入的区域x坐标
            $src_y = ($source_height - $source_width)/2;//设定载入图片要载入的区域y坐标
            $src_w = $source_width;//原图要载入的宽度
            $src_h = $source_width;//原图要载入的高度
        } else {
            $src_x = ($source_width - $source_height)/2;//设定载入图片要载入的区域x坐标
            $src_y = 0;//设定载入图片要载入的区域y坐标
            $src_w = $source_height;//原图要载入的宽度
            $src_h = $source_height;//原图要载入的高度
        }
        $new=imagecreatetruecolor($dst_w, $dst_h);
        $img=imagecreatefromjpeg($source);
        imagecopyresampled($new, $img, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
        imagejpeg($new, $newName);
        imagedestroy($new);
        imagedestroy($img);
    }

    private static function cutImg($source, $newPath, $type, $getWidth = false){
        list($source_width, $source_height)=getimagesize($source);//获取原图片高度、宽度
        if($getWidth){
            return $source_width;
        }
        if($type == 's'){
            $dst_w = 100;
            $dst_h = 100;
            if ($source_width < $source_height) {
                $src_x = 0;//设定载入图片要载入的区域x坐标
                $src_y = ($source_height - $source_width)/2;//设定载入图片要载入的区域y坐标
                $src_w = $source_width;//原图要载入的宽度
                $src_h = $source_width;//原图要载入的高度
            } else {
                $src_x = ($source_width - $source_height)/2;//设定载入图片要载入的区域x坐标
                $src_y = 0;//设定载入图片要载入的区域y坐标
                $src_w = $source_height;//原图要载入的宽度
                $src_h = $source_height;//原图要载入的高度
            }
            $new=imagecreatetruecolor($dst_w, $dst_h);
            $img=imagecreatefromjpeg($source);
            imagecopyresampled($new, $img, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
            imagejpeg($new, $newPath);
            imagedestroy($new);
            imagedestroy($img);
        }elseif($type == 'm'){
            $dst_w = 500;
            $dst_h = $dst_w * $source_height / $source_width;
            $new=imagecreatetruecolor($dst_w, $dst_h);
            $img=imagecreatefromjpeg($source);
            imagecopyresampled($new, $img, 0, 0, 0, 0, $dst_w, $dst_h, $source_width, $source_height);
            imagejpeg($new, $newPath);
            imagedestroy($new);
            imagedestroy($img);
        }
        return true;
    }

    public function updateGroupCredits($fid) {
        C::t('forum_forum')->update_commoncredits($fid);
    }

    public function creditHook($uid, $action, $type = 1) {
        $rule = $this->table('common_credit_rule')->where('action', $action)->find();
        $history = $this->table('common_credit_rule_log')->where(['uid'=>$uid, 'rid'=>$rule['rid']])->find();
        $cre = 'extcredits2';
        $credits = $newRecord = $addRecord = 0;
        //判断是否过了时间
        switch ($rule['cycletype']){
            case '0' ://一生就一次
                if($history){
                    return true;
                }
                $newRecord = $rule[$cre];
                break;
            case '1' ://每天一次
                if(!$history){
                    $newRecord = $rule[$cre];
                }elseif($history['dateline'] < strtotime(date('Ymd', time()).'000000')){//今天零点
                    $addRecord = $rule[$cre];
                }else{
                    return true;
                }
                break;
            case '2' ://整点
                if(!$history){
                    $newRecord = $rule[$cre];
                }elseif($history['dateline'] < strtotime(date('YmdH', time()).'0000')){//当前整点
                    $addRecord = $rule[$cre];
                }else{
                    return true;
                }
                break;
            case '3' ://每分钟
                if(!$history){
                    $newRecord = $rule[$cre];
                }elseif($history['dateline'] < strtotime(date('YmdHi', time()).'00')){//当前整点
                    $addRecord = $rule[$cre];
                }else{
                    return true;
                }
            break;
                break;
            case '4' ://随便
                if(!$history){
                    $newRecord = $rule[$cre];
                }else{
                    $addRecord = $rule[$cre];
                }
                break;
            default : return false;
        }
        if($newRecord && $type){
            $log = $this->table('common_credit_rule_log')->store([
                'uid' => $uid,
                'rid' => $rule['rid'],
                'fid' => 0,
                'total' => $newRecord,
                'cyclenum' => 1,
                'starttime' => time(),
                'dateline' => time()
            ]);
            if(!$log){
                return false;
            }
        }elseif($addRecord){
            if($type) {
                $increaseData = ['total'=>$rule[$cre]];
            }else{
                $increaseData = ['total'=>['-', $rule[$cre]]];
            }
            $log = $this->table('common_credit_rule_log')->where(['uid'=>$uid, 'rid'=>$rule['rid']])->increase($increaseData, ['dateline'=>getglobal('timestamp')]);
            if(!$log){
                return false;
            }
        }
        if($newRecord){
            if($type){
                $credits = ['credits'=>$newRecord];
            }else{
                $credits = ['credits'=>['-', $newRecord]];
            }
        }
        if($addRecord){
            if($type){
                $credits = ['credits'=>$addRecord];
            }else{
                $credits = ['credits'=>['-', $addRecord]];
            }
        }
        $res = $this->table('common_member')->where('uid', $uid)->increase($credits);
        return $res ? $rule[$cre] : false;
    }
}