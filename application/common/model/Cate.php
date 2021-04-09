<?php
namespace app\common\model;
use think\Model;

class Cate extends Base{
    public function getTree(){
        $data = $this->select();
        return $this->tree($data);
    }

    public function tree($data,$parent_id=0,$level=0){
        static $ret = array();
        foreach ($data as $k => $v) {
            if ($v['parent_id']==$parent_id) {
                $v['level'] = $level;
                $ret[] = $v;
                $this->tree($data,$v['id'],$level+1);
            }
        }
        return $ret;
    }

    public function getChild($cate_id){
        $data = $this->select();
        return $this->child($data,$cate_id);
    }
    
    public function child($data,$parent_id){
        static $ret = array();
        foreach ($data as $k => $v) {
            if ($v['parent_id']==$parent_id) {
                $ret[] = $v['id'];
                $this->child($data,$v['id']);
            }
        }
        return $ret;
    }
}
