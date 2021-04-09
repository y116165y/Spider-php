<?php
namespace app\api\controller\v1;
use app\common\lib\exception\ApiException;
use app\common\lib\Aes;
use think\Controller;

class Test extends Common{
    public function index(){
        return show(200,"OK",["id"=>001,'name'=>"kack"]);
    }

    public function lst(){
        $data = input('post.');
        return show(1, 'OK', (new Aes())->encryt(json_encode(input('post.'))), 201);
    }
}