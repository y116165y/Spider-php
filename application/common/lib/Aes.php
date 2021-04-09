<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/15
 * Time: 15:48
 */

namespace app\common\lib;

class Aes
{
    private $key = null;

    /**
     * Aes constructor.
     */
    function __construct()
    {
        $this->key = config('app.aeskey');
    }

    /**
     * 加密 客户端工程师也需要相应的加密模式和填充方式
     * @param string $input
     * @return string
     */
    public function encryt($input = '')
    {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);

        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);

        return $data;

    }

    /**
     * 填充方式 pkcs5
     * @param string $text 原始字符串
     * @param string $blocksize 加密长度
     * @return  string
     */
    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 解密
     * @param string $sStr 解密的字符串
     * @return string bool|string  解密的key
     * @return string
     */
    public function decrypt($sStr)
    {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,
            $this->key, base64_decode($sStr), MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);

        return $decrypted;
    }

}