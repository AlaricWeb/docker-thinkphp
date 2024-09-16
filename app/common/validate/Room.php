<?php
declare (strict_types = 1);

namespace app\common\validate;
use think\Validate;
use think\facade\Db;

class Room extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
       "title"=>"require",
       "user_id"=>"require",
       "room_id"=>"require|exists:room,id"
    ];
    protected $field = [
      'room_id'=>"房间"
    ];
    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     * @var array
     */
    protected $message = [

    ];
    protected function exists($value, $rule, $data=[],$field,$message)
    {   
         if(is_string($rule)){
            $rule = explode(',',$rule);
         }
         $result  = Db::table($rule[0])->where($rule[1],$value)->cache(true)->find();
         if(!$result){
            return  ":attribute 不存在";
         }
         return true;
    }
   
   public function sceneLogin(){
        return $this->only(['user_id',"room_id"]);
   }
   
   public function sceneCreate(){
        return $this->only(["name"]);
   }
    public function sceneBetting(){
         return $this->only(['game_id']);
    }
}
