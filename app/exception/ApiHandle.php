<?php


namespace app\exception;


use think\Exception;

class ApiHandle  extends  Exception
{

    public  $msg = '出现错误';
    public  $error_code = 500;
    public function __construct($message = "", $code = 500)
    {
        $this->msg = $message;
        $this->error_code = $code;
        parent::__construct($message, $code);
    }
    
}