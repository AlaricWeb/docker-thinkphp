<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Cache;

// 应用公共文件
/**
 * 密码加密
 */
if (!function_exists('pass_encryption')) {
    function pass_encryption($pass)
    {
        $p = md5($pass);
        return $p;
    }
}


if(!function_exists('getTokenByUid')){
    function getTokenByUid($prefix,$uid) {
        $token_uid = sprintf("%s:uid:%s", $prefix,$uid);
        $token =  Cache::store('redis')->get($token_uid);
        if($token){
            return false;
        }
        $token_id  = sprintf("%s:token:%s", $prefix,$token); 
        $data =  Cache::store('redis')->get($token_id);
        if(!$data){
            return false;
        }
        return $data;
    }
}
if(!function_exists("getInfoByToken")){
    function getInfoByToken($prefix,$token) {
        $toekn_id = sprintf("%s:token:%s",$prefix,$token);
        $info =Cache::store('redis')->get($toekn_id);
        if(!$info){
            return false;
        }
        
        return $info;
    }
}
if (!function_exists('set_token')) {
    function set_token($prefix, $data,$uid, $expiresIn = 3600)
    {
        $token = md5(sha1(substr(time(), 3, 7)));
        $toekn_id = sprintf("%s:token:%s", $prefix,$token);
        $toekn_uid = sprintf("%s:uid:%s", $prefix,$uid);
        $data_result = Cache::store('redis')->set($toekn_id, $data, $expiresIn);
        if (!$data_result) {
            return false;
        }
        $uid_result = Cache::store('redis')->set($toekn_uid, $token, $expiresIn);
        if (!$uid_result) {
            return false;
        }
        // 存储用户 ID 和令牌的映射
        return $token;
    }
}
if(!function_exists('createToken')){
    function createToken($value){
        $token = md5(sha1(substr(time(), 3, 7)));
        $uniqueID  =  sprintf("login:h5:%s",$token);
        return $token;
    }
}
/**
 * 根据token 获取token 信息 
 */
if(!function_exists('getByToken')){
     function getByToken($token){
        $uniqueID  =  sprintf("login:h5:%s",$token);
        return Cache::get($token);
     }
}
if (!function_exists('builder_tree')) {
    /**
     * @param $data
     * @param $require_key
     * @param $id
     * @param $pid
     * @param $children
     * @return array
     */
    function builder_tree($data, $require_key = [], $id = 'id', $pid = 'parent_id', $children = 'children')
    {
        $build = array_column($data, null, 'id');
        if (!empty($require_key)) {
            $build = array_map(function ($item) use ($require_key) {
                $v = [];
                foreach ($require_key as $key) {
                    $v[$key] = $item[$key];
                }
                return $v;
            }, $build);
        }
        $tree = [];
        foreach ($build as $key => $value) {
            if (isset($build[$value[$pid]])) {
                $build[$value[$pid]][$children][] = &$build[$key];
            } else {
                $tree[] =& $build[$key];
            }
        }
        return $tree;
    }
}

if (!function_exists('get_url_content')) {
    function get_url_content($url)
    {
        $content = file_get_contents($url);
        return json_decode($content, true);
    }
}


/**
 * 返回数组中指定多列
 *
 * @param  Array  $input       需要取出数组列的多维数组
 * @param  String $column_keys 要取出的列名，逗号分隔，如不传则返回所有列
 * @param  String $index_key   作为返回数组的索引的列
 * @return Array
 */
function array_columns($input, $column_keys=null, $attache_array=[]){
    $keys =  explode(',', $column_keys);
    function filterKey($item){
        return 'fsdfdsf';
    }
    return array_map('filterKey',$input);
}



function curlRequest($url, $method = 'GET', $headers = [], $body = null, $timeout = 30) {
    // 初始化 CURL
    $ch = curl_init();
    
    // 设置通用 CURL 选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    // 设置请求方法
    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($body)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            break;
        case 'PUT':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if (!empty($body)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }
            break;
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            break;
        default: // GET as default
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            break;
    }

    // 设置请求头
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // 记录开始时间
    $start_time = microtime(true);

    // 执行请求
    $response = curl_exec($ch);

    // 计算请求耗时
    $info = curl_getinfo($ch);
    $total_time = $info['total_time'];

    // 检查请求错误
    if ($response === false) {
        $error = curl_error($ch);
    } else {
        $error = null;
    }

    // 关闭 CURL
    curl_close($ch);

    // 记录结束时间
    $end_time = microtime(true);
    $total_execution_time = $end_time - $start_time;
    // 如果响应的内容类型是JSON，尝试将其解析为数组
    if (isset($info['content_type']) && strpos($info['content_type'], 'application/json') !== false) {
        $response = json_decode($response, true);
    }

    // 返回响应结果
    return [
        'response' => $response,  // 返回原始的JSON响应或解析后的数组
        'total_time' => $total_time, // CURL 计算的请求耗时
        'execution_time' => $total_execution_time, // 脚本执行的总时间
        'http_code' => $info['http_code'],
        'error' => $error,
        'info' => $info
    ];
}