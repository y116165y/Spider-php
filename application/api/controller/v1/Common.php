<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/15
 * Time: 15:33
 */

namespace app\api\controller\v1;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Cache;
use app\common\lib\exception\ApiException;

use think\Controller;

class Common extends Controller{
    public $headers='';

    public $page = 1;
    public $size = 5;
    public $from = 0;

    public function _initialize(){
        $this->checkRequestAuth();
        //return $this->testAes();
    }

    public function checkRequestAuth(){
        $headers = request()->header();
        //dump($this->testAes());die;
        //sign加密需要客户端工程师 解密服务端工程师

        //基础检验
        if (empty($headers['sign'])) {
            throw new ApiException('sign不存在', 400);
        }
        if (!in_array($headers['app_type'], config('app.apptypes'))) {
            throw new ApiException('app_type不合法', 400);
        }

        if (!IAuth::checkSignPass($headers)) {
           throw new ApiException('授权码sign失败',401);
        }

        Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));

        //1.文件缓存(单一服务器) 2.mysql(分布式) 3 redis（分布式 数据量大）
        $this->headers=$headers;
    }

    /**
     * 通用化获取Api数据中cate_id对应的名称
     * @param  arr $cate_type 获取的新闻列表接口数据
     */
    function getCateName($cate_type){
        if (empty($cate_type)) {
            return [];
        }
        $type = config('catetype.list');
        foreach ($cate_type as $k => $v) {
            $cate_type[$k]['catename'] = $type[$v['cate_id']]?$type[$v['cate_id']]:'';
        }
        return $cate_type;
    }

    /**
     * 通用化获取Api数据列表分页
     * @param  arr $data 获取的新闻列表接口数据
     */
    public function getPageAndSize($data)
    {
        $this->page = !empty($data['page']) ? $data['page'] : 1;
        $this->size = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        $this->from = ($this->page - 1) * $this->size;
    }


    public function testAes()
    {
        //$str = "id=1&ms=45&username=singwa";
        //6dDiaoQrSC2tPepBYWGFh8ri8FNeKXBwRFKbn3hv8qA=
        //echo (new Aes())->encryt($str);

        //$str = "6dDiaoQrSC2tPepBYWGFh8ri8FNeKXBwRFKbn3hv8qA=";
        //id=1&ms=45&username=singwa
        //echo (new Aes())->decrypt($str);

        $data = [
            'did'=>'12345dg',
            'version'=>1,
            'time' => Time::get13TimeStamp(),
        ];

        //col9j6cqegAKiiey3IrXWlVexSJEslQ2aYY+o9uldSEIVnKDb7Rin03dOqY2qLWP
        //echo IAuth::setSign($data);

        $str="col9j6cqegAKiiey3IrXWtsO5k8N1wvQZg5GUXq76mYIVnKDb7Rin03dOqY2qLWP";
        echo (new Aes())->decrypt($str);//did=12345dg&version=1

        exit;
    }
}