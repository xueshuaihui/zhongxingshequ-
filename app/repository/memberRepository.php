<?php
require_once 'baseRepository.php';

class memberRepository extends baseRepository {
    public function updateUserProfile($uid, $data = []) {
        return $this->table('common_member_profile')->where('uid', $uid)->update($data);
    }

    public function searchFriend($uid, $keyword) {
        $data = $this->table('home_friend')
            ->ass('f')
            ->join(' LEFT JOIN '.$this->prefix.'common_member AS u ON f.fuid = u.uid')
            ->join(' LEFT JOIN '.$this->prefix.'common_member_profile AS p ON f.fuid = p.uid')
            ->where('f.uid', $uid)
            ->whereWhere('u.username', 'LIKE', "%{$keyword}%")
            ->select('u.uid, u.username, p.bio');
        foreach ($data as $k=>$value){
            $data[$k]['usericon'] = $this->getAvatar($value['uid']);
        }
        return $data;
    }

    public function addFriendApply($uid, $username, $who) {
        return $this->table('home_friend_request')->store([
            'uid' => $uid,
            'fuid'=> $who,
            'fusername' => $username,
            'gid' => 1,
            'note' => 'app端申请加好友',
            'dateline'=>time()
        ]);
    }
}