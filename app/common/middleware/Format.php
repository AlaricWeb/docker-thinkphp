<?php
declare (strict_types = 1);

namespace app\common\middleware;

class Format
{
    /**
     * Summary of handle
     * @param mixed $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
         $response = $next($request);
          $content =  $response->getData();
        if(is_object($content) || is_array($content) ){
            if(isset($content['error_code'])){
               return  json($content)->code($response->getCode());
            }
            $data['msg'] = '请求成功';
            $data['code'] = 200;
            $data['result']=  $content ;
            $content = $data;
            return  json($data)->code(200);
        } 
        return $response;
    }
}
