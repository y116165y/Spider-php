<?php
/**
 * 爬虫爬数据
 */

namespace app\admin\controller;
use vendor\autoload;
use think\Controller;
use QL\QueryList;
use think\Db;

class Spider1 extends Controller{
    public function index(){
       $NO = ["channel_25950","channel_25951","channel_25952","channel_25953"];
       foreach ($NO as $k => $v) {
            $url[] = "https://www.thepaper.cn/".$v;
       }
       array_unshift($url,"https://www.thepaper.cn/");
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
                'maxThread' => 300,
                //设置最大尝试数
                'maxTry' => 3 
            ],
            'success' => function($a){
                //采集规则
                $reg = [
                    'title' => ['.news_li h2 a','text'],
                    'image' => ['.news_li .news_tu a img','src','',function($img){
                        return $this->getImage("http:".$img);
                    }],
                    'datafrom' => ['.news_li .pdtt_trbs a','text'],
                    'des' => ['.news_li p','text'],
                    'link' => ['.news_li h2 a','href','',function($e){
                        return "https://www.thepaper.cn/".$e;
                    }]
                ];
                //dump($a);die;
                $ql = QueryList::Query($a['content'],$reg)->getData(function($item){
                    $rules1 = [
                        'reporter' => ['.newscontent .news_about p:eq(0)','text'],
                        'con' => ['.newscontent .news_txt','html'],
                    ];
                    $data1 = QueryList::Query($item['link'],$rules1)->data;
                    foreach ($data1 as $k => $v) {
                        $item['author'] = $data1[0]['reporter'];
                        $item['content'] = $data1[0]['con'];
                        $item['update_time'] = time();
                    }
                    return $item;
                });
                //打印结果，实际操作中这里应该做入数据库操作
                $aurl = substr($a['info']['url'],-5);
                $result = array();
                foreach ($ql as $k => $v) {
                    $ql[$k]['cate_id'] = strpos($aurl,'/')?25000:(int)$aurl;
                    $ql[$k]['status'] = 1;
                    if (!isset($ql[$k]['author'])) {
                        $ql[$k]['author'] = "无";
                    }
                    if (!isset($ql[$k]['content'])) {
                        $ql[$k]['content'] = "暂无内容";
                    }
                    unset($ql[$k]['link']);
                 }
                foreach ($ql as $key => $value) {
                     $result[] = $value;
                 }
                 //数组去重
                 $key = 'title';
                $result1 = $this->array_unique1($result,$key);
                try{
                    model('article')->insertAll($result1);
                    unset($result1);
                }catch(\exception $e){
                    echo $e->getMessage();
                }
            }
        ]);        
    }

    /**
     * 二维数组去重
     * $arr二维数组，$key一维数组字段
     */
    public function array_unique1($arr, $key){
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        sort($arr); //sort函数对数组进行排序
        return $arr;
    }

    public function downImage($file){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$file);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $tmp = curl_exec($ch);
        curl_close($ch);   
    }
  
    public function deleteNews(){
       $date = db('article')->whereTime('update_time','<',date("Y-m-d",strtotime("-3 day")))->delete();
    }

    public function getImage($img){
        //保存至固定目录
        $path = 'spiderimg'. DS . rand(1,99999999).'.jpg';
        $r = file_get_contents($img);
        file_put_contents(IMG_UPLOADS. DS . $path, $r);
        return $path;
    }
}
