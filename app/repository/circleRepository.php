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
}