<?php

namespace app\common\model;

use think\Db;
use think\Model;

class User extends Model{
    //Reader
    //登录
    public function login($data)
    {
        //检查
        $validate = new \app\common\validate\User();
        if($validate->scene('login')->check($data)==0)
        {
            return $validate->getError();
        }

        //数据库
        $res=$this->where($data)->find();
        if($res)
        {
            return $res['name'];
        }else{
            return "用户名不存在或密码错误";
        }
    }
    //注册
    public function register($data)
    {
        //检查
        $validate = new \app\common\validate\User();
        if($validate->scene('register')->check($data)==0)
        {
            return $validate->getError();
        }

        //数据库
        $res=$this->where('userid',$data['userid'])->find();
        if($res==null)
        {
            $data2=[
                'userid'=>$data['userid'],
                'password'=>$data['password'],
                'role'=>$data['role'],
                'Email'=>$data['Email'],
                'phone'=>$data['phone'],
                'name'=>$data['name'],
            ];
            Db::name('Reader')->insert($data);
            $this->insert($data2);
            return 1;
        }else{
            return "该用户名已被注册！";
        }
    }
    //更新信息
    public function updateUser($data)
    {
        $validate = new \app\common\validate\User();
        if($validate->scene('update')->check($data)==0)
        {
            return $validate->getError();
        }
        $this->where('userid',$data['userid'])
            ->update(['name'=>$data['name'],'phone'=>$data['phone'],'Email'=>$data['Email']]);
    }

}