<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2017/11/15
 * Time: 14:13
 */

namespace app\common\lib\exception;

use Exception;
use think\exception\Handle;

class ApiHandleException extends Handle
{
    /**
     * http状态码
     * @var int 500 内部错误
     */
    public $httpCode = 500;
    public function render(Exception $e){
        if (config('app_debug') == true) {
            return parent::render($e);
        }
        if ($e instanceof ApiException) {
            $this->httpCode = $e->httpCode;
        }
        return show(0, $e->getMessage(), [], $this->httpCode);
    }
}