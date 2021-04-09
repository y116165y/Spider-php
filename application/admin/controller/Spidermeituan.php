<?php
/**
 * 爬虫爬取美团数据
 */

namespace app\admin\controller;
use vendor\autoload;
use think\Controller;
use QL\QueryList;

//https://i.meituan.com/bengbu/all/?cid=36&bid=2876&stid_b=4&cateType=poi&p=1 蚌山小吃
//https://i.meituan.com/bengbu/all/?cid=36&bid=2877&stid_b=4&cateType=poi&p=1 龙子湖小吃
//https://i.meituan.com/bengbu/all/?cid=36&bid=2878&stid_b=4&cateType=poi&p=1 禹会小吃
//https://i.meituan.com/bengbu/all/?cid=36&bid=2879&stid_b=4&cateType=poi&p=1 淮上小吃
//https://i.meituan.com/bengbu/all/?cid=36&bid=2880&stid_b=4&cateType=poi&p=1 怀远小吃
//https://i.meituan.com/bengbu/all/?cid=36&bid=2881&stid_b=4&cateType=poi&p=1 五河小吃
class Spidermeituan extends Controller{
    public function index(){
        $url=array();
        for ($i=1; $i <= 13 ; $i++) { 
            $url[] = 'https://i.meituan.com/bengbu/all/?cid=36&bid=2876&cateType=poi&stid_b=4&p='.$i;
        }
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
                'maxThread' => 1000,
                //设置最大尝试数
                'maxTry' => 3 
            ],
            'success' => function($a){
                //采集规则
                $reg = [
                    'name' => ['.list .poi-list-item .react .poiname','text'],
                    'area' => ['.list .poi-list-item p a','text'],
                    'star' => ['.list .poi-list-item .react .kv-line-r h6 .stars em','text'],
                    'link' => ['.list .poi-list-item .react','href'],
                    'goods_des' => ['.list .bd-deal-list','html']
                ];
                $ql = QueryList::Query($a['content'],$reg)->getData(function($item){
                    $rules = [
                        'name' => ['a .text-block','text'],
                        'price' => ['a .price .strong','text'],
                        'sale_count' => ['.posi-right-bottom .statusInfo','text'],
                    ];
                    $item['g_name'] = QueryList::Query($item['goods_des'],$rules)->data;
                    return $item;
                });
                $goods = array();
                //打印结果，实际操作中这里应该做入数据库操作
                //dump($ql);
                foreach ($ql as $key => $value) {
                    $ql[$key]['datafrom'] = 1;
                    $ql[$key]['cate_id'] = 10;
                    $ql[$key]['city'] = '蚌埠,蚌山';
                    $ql[$key]['update_time'] = time();
                    $goods[] = $value['g_name'];
                    unset($ql[$key]['g_name']);
                    unset($ql[$key]['goods_des']);
                }
                try{
                    model('bis')->insertAll($ql);
                    unset($ql);
                }catch(\exception $e){
                    echo $e->getMessage();
                }
            }
        ]);
    }
   
}