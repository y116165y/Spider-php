<?php
/**
 * 获取各地区15日天气
 */

namespace app\admin\controller;
use think\Controller;

class Weather extends Controller{
    public function index(){

    }

    public function lst(){
        $input = input('city');
        $json_string = json_decode(file_get_contents(APP_PATH . 'extra' . DS . '_city.json'),true);
        $citys = array();
        foreach ($json_string as $k => $v) {
            $citys[] = $v['city_name'];
        }
        $kc = array_search($input,$citys);
        $data = "http://t.weather.sojson.com/api/weather/city/".$json_string[$kc]['city_code'];
        $html = json_decode(file_get_contents($data),true);
        $weather = $html['data']['forecast'];
        foreach ($weather as $k => $v) {
            $weather[$k]['lowst'] = trim(str_replace('低温','',$v['low']));
            $weather[$k]['highst'] = trim(str_replace('高温','',$v['high'])); 
        }
        $data = $html['data'];
        $weather1 = array_slice($weather,0,7);
        return $this->fetch('',['weather1'=>$weather1,
            'html'=>$html,
            'data'=>$data,
            ]);
    }
}
