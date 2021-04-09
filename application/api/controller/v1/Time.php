<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/22
 * Time: 9:55
 */

namespace app\api\controller;

use think\Controller;

class Time extends Controller
{
    public function index()
    {
        return time();
    }
}