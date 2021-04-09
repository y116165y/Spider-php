<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 应用公共文件
// 应用公共文件

function isYesNo($str){
    return $str ? '<span>是</span>':'<span>否</span>';
}


function getCatetype($cate_type){
    $type = config('catetype.list');
    return !empty($type[$cate_type]) ? $type[$cate_type]:'';
}

function getWeatherPng($type){
    $PNG = config('catetype.weatherpng');
    return !empty($PNG[$type]) ? $PNG[$type]:'';
}


/**
 * 通用化API接口数据输出
 * @param  int $status 业务状态码
 * @param string $message 信息提示
 * @param [] $data 数据
 * @param int $httpcode http状态码
 * @return \think\response\Json
 */
function show($status, $message, $data = [], $httpcode = 200)
{
    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
    return json($data, $httpcode);
}

