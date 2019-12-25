<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2018/10/24
 * Time: 16:09
 */
namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\ValidateException;

class HttpException extends Exception
{
    public function render(\Exception $e)
    {
        if(config('app_debug'))
        {
            // 参数验证错误
            if ($e instanceof ValidateException) {
                return json($e->getError(), 422);
            }

            // 请求异常
            if ($e instanceof HttpException && request()->isAjax()) {
                return response($e->getMessage(), $e->getStatusCode());
            }

            // 其他错误交给系统处理
            return parent::render($e);
        }
        else
        {
            header("Location:".url('/error'));
        }
    }
}