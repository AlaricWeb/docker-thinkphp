<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'Lottery' => 'app\common\command\Lottery',
        "redis:subscribe"=>'app\common\command\Subscribe'
    ],
];
