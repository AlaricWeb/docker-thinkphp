<?php
namespace app\exception;

use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    public function render($request, Throwable $e): Response
    {
        // 其他错误交给系统处理
        if($e instanceof ApiHandle){
            return json([
                'msg'=>$e->msg,
                'error_code'=>$e->error_code
            ], 423);
        }
        // 参数验证错误
        if ($e instanceof ValidateException) {
            if(!is_array($e->getError())){
                return json([
                    'msg'=>$e->getError(),
                    "error_code"=>400
                ], 422);
            }
            $err_data = $e->getError();
            return json($err_data,$err_data['code']);
        }
        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

}