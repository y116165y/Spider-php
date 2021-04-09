<?php
/**
 * 获取IP具体所在位置
 */

namespace app\admin\controller;
use think\Controller;

class Ipplace extends Controller{
    public function index(){
        echo "hu";
        // $url = "url='http://api.map.baidu.com/highacciploc/v1?qcip=183.55.116.95&qterm=pc&ak=lFVmmPGVELgSH29Tui0fFw9uZXKCjMAX&coord=bd09ll&extensions=3'";
        // $html = json_decode(file_get_contents($url),true);
        // dump($html);
    }
}