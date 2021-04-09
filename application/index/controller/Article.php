<?php
namespace app\index\controller;
use think\Controller;

class Article extends controller
{
    public function index($id)
    {
        $art = model('article')->where('id',$id)->find();
        return $this->fetch('',['art'=>$art]);
    }
}
