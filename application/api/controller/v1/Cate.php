<?php
namespace app\api\controller\v1;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use think\Controller;

class Cate extends Common{
    public function read(){
        $cate = config('catetype.list');
        $result []= [
            'catid'=>0,
            'catname'=>'首页',
        ];
        foreach ($cate as $k => $v) {
            $result[] = [
                'cate_id' => $k,
                'catename' => $v,
            ];
         }
        return show(config('code.success'),'OK',$result,200);
    }
}