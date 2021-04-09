<?php
namespace app\admin\controller;
use think\Controller;

class Cate extends Controller{
    public function lst(){
        $cate = model('cate')->getTree();
        $catecate = model('cate')->where(['parent_id'=>0])->select();
        return $this->fetch('',['cate'=>$cate,'catecate'=>$catecate]);
    }

    public function add()
    {
        return view();
    }

    public function edit($id){
        return view();
    }

    public function del(){

    }

    public function addcate(){
        if (request()->isPost()) {
            $data = input('post.');
           $id= model('cate')->add($data);
           if ($id) {
               return 1;
           }else {
               return 0;
           }
        }   
    }

}
