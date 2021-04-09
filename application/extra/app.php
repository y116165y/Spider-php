<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/9
 * Time: 17:32
 */
return [
    'admin_password_pre' => '_#sing_ty',
    'aeskey' => 'sgg45747ss223455',//aes密钥，服务端和客户端必须保持一致
    'apptypes'=>[
        'ios',
        'android',
    ],
    'app_sign_time' => 2592000,//sign失效时间,重点划线
    'app_sign_cache_time' => 60,//sign缓存失效时间
];