<?php
/**
 * 爬虫爬取淘江阴生鲜产品数据
 */

namespace app\admin\controller;
use vendor\autoload;
use think\Controller;
use QL\QueryList;
use think\Db;

class Spidertjy extends Controller{
    public function index(){
        $url = "http://www.taojiangyin.com/goods-37167.html"; 
        $rules = [
            'title' => ['.prodectDetailBox_r .pro_top h1','text','-span'],
            'small_title' => ['.prodectDetailBox_r .pro_top .f_title','text'],
            'image' => ['.prodectDetailBox_l .jqzoom img','src'],
            'shop_price' => ['.prodectDetailBox_r .pro_price .price .goodprice','text'],
            'market_price' => ['.prodectDetailBox_r .pro_price .rev_price .xhx','text'],
            'single_price' => ['.prodectDetailBox_r .pro_price .rev_price em:eq(0)','text'],
            'format' => ['.prodectDetailBox_r .pro_gg .shop_attrs a em','text'],
            'des' => ['.productDeatil_c .main_r .productCont p:eq(1)','html'],
        ];
        $data = QueryList::Query($url,$rules)->data;
        dump($data);
    }
}