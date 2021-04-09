<?php
/**
 * 获取身份证信息
 */

namespace app\admin\controller;
use think\Controller;

class Shenfen extends Controller{
    public function index(){

    }

    public function lst(){
        $card_id = '340321199307183751';
        $key = 'fcdcf884d53045621f3b7c46f9d43640';
        $data = "http://apis.juhe.cn/idcard/index?key=".$key."&cardno=".$card_id;
        $html = json_decode(file_get_contents($data),true);
        $html['card_id'] = $card_id;
        $month = (int)(substr($card_id,11,12));
        $day = (int)(substr($card_id,13,14));
        dump($month.'月'.$day);die;
        //return view();
        return $this->fetch('',['html'=>$html]);
    }

        /* 
        * 计算星座的函数 string get_zodiac_sign(string month, string day) 
        * 输入：月份，日期 
        * 输出：星座名称或者错误信息 
        */ 

        function get_zodiac_sign($month, $day) 
        { 
            // 检查参数有效性 
            if ($month < 1 || $month > 12 || $day < 1 || $day > 31){
                return (false); 
            } 
            // 星座名称以及开始日期 
            $signs = array( 
            array( "20" => "宝瓶座"), 
            array( "19" => "双鱼座"), 
            array( "21" => "白羊座"), 
            array( "20" => "金牛座"), 
            array( "21" => "双子座"), 
            array( "22" => "巨蟹座"), 
            array( "23" => "狮子座"), 
            array( "23" => "处女座"), 
            array( "23" => "天秤座"), 
            array( "24" => "天蝎座"), 
            array( "22" => "射手座"), 
            array( "22" => "摩羯座") 
            ); 
            list($sign_start, $sign_name) = each($signs[(int)$month-1]); 
            if ($day < $sign_start) 
            list($sign_start, $sign_name) = each($signs[($month -2 < 0) ? $month = 11: $month -= 2]); 
            return $sign_name; 
        }//函数结束 
}
