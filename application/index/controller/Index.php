<?php
namespace app\index\controller;
use think\Controller;

class Index extends controller
{
    public function index()
    {
        $article = db('article')->field('id,title,image,des,datafrom')->whereTime('update_time',date("Y-m-d"))->limit(4)->select();
        return $this->fetch('',['article'=>$article]);
    }

    public function ajaxarticle($page=0){         
        $page = input('page');
        $date = db('article')->field('id,title,image,des,datafrom')->whereTime('update_time',date("Y-m-d"))->limit($page,4)->select();
        return show(200,'OK',$date);
    }
}
