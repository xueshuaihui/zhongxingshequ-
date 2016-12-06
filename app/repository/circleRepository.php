<?php
require_once 'baseRepository.php';

class circleRepository extends baseRepository {
    public function getCircleList($uid, $type, $page) {
        /**
         * 0:all  1: manage  2: mine
         */
        $list = $this->table()->grouplist($uid, $type, $page);
        if($type == 2){
            $list1 = $this->table()->grouplist($uid, 1, $page);
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

    public function getGroupProfile($fid) {
        return $this->table('forum_forum')
               ->ass('f')
               ->join(' LEFT JOIN '.$this->prefix.'forum_forumfield AS ff ON f.fid = ff.fid')
               ->where('f.fid', $fid)
               ->find();
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

    public function applyJoin($uid, $fid, $username, $admin = false) {
        return $this->table('forum_groupuser')->store([
            'uid' => $uid,
            'fid' => $fid,
            'username'=>$username,
            'level' => $admin?4:0,
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

    public function quit($fid, $uid) {
        return $this->table('forum_groupuser')->where(['fid'=>$fid, 'uid'=>$uid])->delete();
    }

    public function getGroupUser($fid, $field, $level = 1, $forceall = false) {
        if($forceall){
            return $this->table('forum_groupuser')
                ->where(['fid'=>$fid])
                ->select($field);
        }elseif($level || $level === 0){
            return $this->table('forum_groupuser')
                ->where(['fid'=>$fid, 'level'=>$level])
                ->select($field);
        }else{
            return $this->table('forum_groupuser')
                ->where('fid', $fid)
                ->whereWhere('level', '>', 0)
                ->select($field);
        }
    }

    public function getUserFromGroup($uid, $fid) {
        return $this->table('forum_groupuser')->where(['uid'=>$uid, 'fid'=>$fid])->find();
    }

    public function updateGroupUser($uid, $fid, $power) {
        $update = $this->table('forum_groupuser')->where(['uid'=>$uid, 'fid'=>$fid])->update(['level'=>$power]);
        if($update){
            update_groupmoderators($fid);
            return true;
        }
        return false;
    }

    public function updateGroupProfile($fid, $data = []) {
        return $this->table('forum_forumfield')->where('fid', $fid)->update($data);
    }

    public function ignoreApply($uid, $fid) {
        $res = $this->table('forum_groupuser')->where(['uid'=>$uid, 'fid'=>$fid])->delete();
        if($res){
            update_groupmoderators($fid);
            return true;
        }
        return false;
    }

    public function getInviteUser($fid, $uid) {
        return $this->table('forum_groupinvite')
                    ->ass('i')
                    ->join(' LEFT JOIN '.$this->prefix.'common_member AS u ON i.inviteuid = u.uid')
                    ->where(['i.fid'=>$fid, 'i.uid'=>$uid])
                    ->select('u.uid, u.username');
    }

    public function inviteUser($uid, $fid, $invites) {
        foreach ($invites as $invite){
            $res = $this->table('forum_groupinvite')
                 ->store([
                    'fid'=>$fid,
                    'uid'=>$uid,
                    'inviteuid'=>$invite,
                    'dateline' => getglobal('timestamp')
                ], false);
            if(!$res){
                return false;
            }
        }
        return true;
    }
}