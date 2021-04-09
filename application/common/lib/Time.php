<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/21
 * Time: 16:00
 */

namespace app\common\lib;


class Time
{
    /**
     * 获取13位的时间戳
     * @return int
     */
    public static function get13TimeStamp(){
        //string '0.67408200 1551756225' (length=21)
        //halt(microtime());

        list($t1, $t2) = explode(' ', microtime());
        return $t2 . ceil($t1 * 1000);
    }
}