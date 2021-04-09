<?php
namespace app\api\controller\v1;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use think\Controller;

class Index extends Common{
    public function index(){
        $heads = model('article')->getIndexHadNormalNews();
        $heads = $this->getCateName($heads);
        $positions = model('article')->getPositionNormalNews();
        $positions = $this->getCateName($positions);
        $result = [
            'heads' => $heads,
            'positions' => $positions,
        ];

        return show(config('code.success'), 'OK', $result, 200);
    }
}