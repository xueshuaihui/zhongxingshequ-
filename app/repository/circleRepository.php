<?php
require_once 'baseRepository.php';

class circleRepository extends baseRepository {
    public function getCircleList($uid, $type, $page) {
        require_once ROOT.'source'.__.'function'.__.'function_group.php';
        $list = $this->table()->mygrouplist($uid, $type, $page);
        if($type = 2){
            $list1 = $this->table()->mygrouplist($uid, 1, $page);
            $list = array_merge($list, $list1);
        }
        $res = [];
        foreach ($list as $k=>$value){
            $res[$k]['icon'] = BASEURL.__.$value['icon'];
            $res[$k]['name'] = $value['name'];
            $res[$k]['fid'] = $value['fid'];
            $res[$k]['description'] = $value['description'];
        }
        return $res;
    }

    public function searchCircle($keyword) {
        $list = $this->table()->searchGroup($keyword);
        $res = [];
        foreach ($list as $k=>$value){
            $res[$k]['icon'] = BASEURL.__.$value['icon'];
            $res[$k]['name'] = $value['name'];
            $res[$k]['fid'] = $value['fid'];
            $res[$k]['description'] = $value['description'];
        }
        return $res;
    }

    public function applyJoin($uid, $fid, $username) {
        return $this->table('forum_groupuser')->store([
            'uid' => $uid,
            'fid' => $fid,
            'username'=>$username,
            'level' => 0,
            'threads' => 0,
            'replies' => 0,
            'joindateline' => getglobal('timestamp'),
            'lastupdate' => 0,
            'privacy' => 0
        ],false);
    }

    public function getGroup($fid) {
        return $this->table('forum_forum')->where('fid', $fid)->find();
    }

    public function getGroupUser($fid, $field, $level = 1) {
        return $this->table('forum_groupuser')
                    ->where(['fid'=>$fid, 'level'=>$level])
                    ->select($field);
    }
}