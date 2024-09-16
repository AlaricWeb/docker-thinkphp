<?php  
namespace app\common\command;

use app\common\model\lottery\LotteryDraw;
use Swoole\Timer;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\facade\Cache;

class Lottery extends Command
{
    protected function configure()
    {
        $this->setName('Lottery')
            ->setDescription('Fetch lottery data from multiple interfaces concurrently')
            ->addOption('data',null, Option::VALUE_REQUIRED, '数据');
    }
    protected function execute(Input $input, Output $output)
    {
        $data = json_decode($input->getOption('data'),true);
        if(!$data){
            $output->writeln("数组错误");
            return -1;
        }
        var_dump($data['preDrawIssue']);
        $draw =  LotteryDraw::where("issue",$data['preDrawIssue'])->find();
       if(!$draw){
           $result = [
            'ball'=>$data['preDrawCode'],
            'issue'=>$data['preDrawIssue'],
            'next_issue'=>$data['drawIssue'],
            "lottery_type_id"=>$data['lottery_type_id'],
            "next_draw_time"=>$data['drawTime']
           ];
           var_dump($result);
            LotteryDraw::create($result);
            Cache::set($data['lotCode'],$result);
        }
    }
}    
