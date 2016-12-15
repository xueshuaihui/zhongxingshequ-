<?php
require_once 'baseRepository.php';

class circleRepository extends baseRepository {
    public function getCircleList($uid, $type, $page) {
        /**
         * 0:all  1: recommend  2: mine
         */
        if($type == 2){
            $listmine = $this->table()->grouplist($uid, 2, $page);
            $listmanage = $this->table()->grouplist($uid, 1, $page);
            $list = array_merge($listmine, $listmanage);
        }elseif($type == 1){
            $list = $this->table('common_setting')->where('skey', 'group_recommend')->find();
            $list = array_values(dunserialize($list['svalue']));
            $start = ($page - 1)*10;
            $end = $start+9;
            foreach ($list as $k=>$v){
                if($k < $start || $k > $end){
                    unset($list[$k]);
                }
            }
        }else{
            $list = $this->table()->grouplist($uid, $type, $page);
        }
        $res = [];
        foreach ($list as $k=>$value){
            $res[$k]['icon'] = BASEURL.__.$value['icon'];
            if($type == 2 ){
                $join = 1;
            }else{
                $join = $this->table('forum_groupuser')->where(['uid'=>$uid, 'fid'=>$value['fid']])->find();
                $join = $join?1:0;
            }
            $res[$k]['join'] = $join;
            $res[$k]['name'] = $value['name'];
            $res[$k]['fid'] = $value['fid'];
            $res[$k]['description'] = $value['description'];
        }
        return array_values($res);
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
            $res[$k]['icon'] = BASEURL.__.($value['icon']?:'static/image/common/groupicon.gif');
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

    public function getUserFromGroup($uid, $fid) {
        return $this->table('forum_groupuser')->where(['fid'=>$fid, 'uid'=>$uid])->find();
    }

    public function deleteUserFromForum($uid, $fid) {
        return $this->table('forum_groupuser')->where('fid', $fid)->in('uid', $uid)->delete();
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

    public function updateGroup($fid, $data = []) {
        return $this->table('forum_forum')->where('fid', $fid)->update($data);
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