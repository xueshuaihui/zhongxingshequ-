<?php
require_once 'baseRepository.php';

class memberRepository extends baseRepository {
    public function updateUserProfile($uid, $data = []) {
        return $this->table('common_member_profile')->where('uid', $uid)->update($data);
    }
}