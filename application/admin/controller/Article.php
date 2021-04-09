<?php
namespace app\admin\controller;
use think\Controller;

class Article extends Controller{
    public function lst(){
        $input = input('post.');
        if (!empty($input)) {
            $data['title'] = ['like','%'.$input['keywordtext'].'%'];
            if ($input['cateid']) {
                $data['cate_id'] = $input['cateid'];
            }
        }
        $data['status'] = ['neq',config('code.delete_status')];
        $article = model('article')->where($data)->select();
        $cate = db('cate')->select();
        return $this->fetch('',['article'=>$article,'cate'=>$cate]);
    }

    public function add()
    {
        return view();
    }

    public function edit($id){
        return view();
    }

    public function del(){
        $id = input('id');
        $del = db('article')->where(['id'=>$id])->delete();
        if ($del) {
            return 1;
        }else{
            return 0;
        }
    }

    
}
