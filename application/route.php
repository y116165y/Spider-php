<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('api/:ver/cate',  'api/:ver.cate/read');
Route::get('api/:ver/test','api/:ver.test/index');  
Route::get('api/:ver/index','api/:ver.index/index');    //首页接口数据
Route::resource('api/:ver/news', 'api/:ver.news');      //列表页接口数据
Route::rule('api/:ver/news/:id','api/:ver.news/read');  //新闻详情页接口