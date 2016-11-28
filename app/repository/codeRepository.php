<?php
require_once 'baseRepository.php';

class codeRepository extends baseRepository {
    public function identityHadPhone($phone) {
        return (bool) $this->table('common_member_profile')->where('mobile', $phone)->find();
    }

    public function getRand() {
        return $this->randNum(4, 1);
    }
}