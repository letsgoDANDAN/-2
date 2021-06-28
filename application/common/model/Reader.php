<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 23:41
 */

namespace app\common\model;


use think\Model;

class Reader extends Model
{
    //Reader
    //根据姓名得到读者
    public function findByName($name)
    {
        $res=$this->where('name',$name)->find();
        if($res) return $res;
    }

    //更新读者图书数量
    public function updateReader($reader)
    {
        if(
            $this->where('userid',$reader['userid'])
            ->update(['booknumber'=>$reader['booknumber'],'cbooknumber'=>$reader['cbooknumber'],'fbooknumber'=>$reader['fbooknumber']])
        ){
            return 1;
        }else return 0;
    }
    //更新读者信息
    public function updateAll($reader)
    {
        $validate = new \app\common\validate\Reader();
        if($validate->scene('update')->check($reader)==0)
        {
            return $validate->getError();
        }

        $validate = new \app\common\validate\User();
        if($validate->scene('update')->check($reader)==0)
        {
            return $validate->getError();
        }
        $this->where('userid',$reader['userid'])
            ->update(['name'=>$reader['name'],'unitID'=>$reader['unitID'],'readertypeID'=>$reader['readertypeID'],'Email'=>$reader['Email'],'phone'=>$reader['phone']]);
    }

}