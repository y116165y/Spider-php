<?php
/**
 * 爬虫爬取淘江阴生鲜产品列表数据
 */

namespace app\admin\controller;
use vendor\autoload;
use think\Controller;
use QL\QueryList;
use think\Db;

class Spidertjys extends Controller{
    public function index(){
        $url=array();
        for ($i=3; $i <=12 ; $i++) { 
            $url[] = "http://www.taojiangyin.com/category.php?id=".$i."01";
        }
        array_unshift($url,"http://www.taojiangyin.com/category.php?id=202");
        //多线程扩展
        QueryList::run('Multi',[
            //待采集链接集合
            'list' => $url,
            'curl' => [
                'opt' => array(
                            //这里根据自身需求设置curl参数
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_AUTOREFERER => true,
                            //........
                        ),
                //设置线程数
                'maxThread' => 100,
                //设置最大尝试数
                'maxTry' => 3 
            ],
            'success' => function($a){
                //采集规则
                $reg = [
                    'title' => ['.proClassify_cont .proList li .showPic a','title'],
                    'link' => ['.proClassify_cont .proList li .showPic a','href','',function($ww){
                        return "http://www.taojiangyin.com/".$ww;
                    }],
                    'small_title' => ['.proClassify_cont .proList li .desc','text'],
                ];
                $ql = QueryList::Query($a['content'],$reg)->getData(function($item){
                    $rules1 = [
                        'image' => ['.prodectDetailBox_l .jqzoom img','src','',function($c){
                            return "http://www.taojiangyin.com/".$c;
                        }],
                        'shop_price' => ['.prodectDetailBox_r .pro_price .price .goodprice','text'],
                        'market_price' => ['.prodectDetailBox_r .pro_price .rev_price .xhx','text'],
                        'single_price' => ['.prodectDetailBox_r .pro_price .rev_price em:eq(0)','text'],
                        'format' => ['.prodectDetailBox_r .pro_gg .shop_attrs a em','text'],
                        'des' => ['.productDeatil_c .main_r .productCont:eq(0)','html'],
                    ];
                    $data1 = QueryList::Query($item['link'],$rules1)->data;
                    foreach ($data1 as $k => $v) {
                        $item['image'] = $data1[0]['image'];
                        $item['shop_price'] = $data1[0]['shop_price'];
                        $item['market_price'] = $data1[0]['market_price'];
                        $item['single_price'] = $data1[0]['single_price'];
                        $item['format'] = $data1[0]['format'];
                        $item['des'] = $data1[0]['des'];
                    }
                    return $item;
                });
                //打印结果，实际操作中这里应该做入数据库操作
                dump($a);
            }
        ]);
    }
}