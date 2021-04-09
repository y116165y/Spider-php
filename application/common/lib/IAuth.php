<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/9
 * Time: 17:35
 */

namespace app\common\lib;
use think\Cache;

class IAuth
{

    public static function setPassword($data){
        return md5($data . config('app.admin_password_pre'));
    }

    /**
     * 生成每次请求的sign
     * @param array $data
     * @return string
     */
    public static function setSign($data = [])
    {
        //1.按字段排序
        ksort($data);
        //2.拼接字符串数据 &
        $string = http_build_query($data);
        //3.通过aes加密
        $string = (new Aes())->encryt($string);
        return $string;
    }

    /**
     * 检查sign是否正常
     * @param string $sign
     * @param $data
     * @return boolean
     */
    public static function checkSignPass($data)
    {
        $str = (new Aes())->decrypt($data['sign']);
        if (empty($str)) {
            return false;
        }
        //diid=xx&app_type=3
        parse_str($str, $arr);

        /**
         * array (size=2)
         * 'did' => string '12345dg' (length=7)
         * 'version' => string '1' (length=1)
         */
        //halt($arr);

        if (!is_array($arr) || empty($arr['did']) || $arr['did'] != $data['did']) {
            return false;
        }
        if (!config('app_debug')) {
            if ((time() - ceil($arr['time'] / 1000)) > config('app.app_sign_time')) {
                return false;
            }
            //唯一性判定
            return true;
            }
        }

}
