<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/16
 * Time: 14:18
 */
namespace app\admin\controller;

use think\Controller;
class User extends Controller
{
    //登录
    public function login()
    {
        if(session('userid')){
            $this->redirect('admin/reader/showbooks');
            return;
        }
        if (request()->isAjax()) {
            $data = [
                'userid' => input('userid'),
                'password' => input('password'),
                'role' =>input('type'),
            ];
            $res = model('user')->login($data);

            if ($res != "用户名不存在或密码错误") {
                if($data['role']=='reader') {
                    session("userid",$res);
                    $this->success('恭喜登录成功！','admin/reader/showbooks');
                }
                else{
                    $this->success("小蔡快写！",'admin/admin/ashowbooks');
                }

            } else {
                $this->error($res);
            }

        }
        return view();
    }
    //载入注册页面
    public function register()
    {
        $rt=model('Readertype')->select();
        $unit=model('Unit')->select();
        $this->assign('rt',$rt);
        $this->assign('unit',$unit);
        return view();
    }
   //注册
    public function doregister()
    {
            $data = [
                'userid' => input('userid'),
                'password' => input('password'),
                'Email' =>input('Email'),
                'phone'=>input('phone'),
                'name'=>input('name'),
                'unitID'=>input('unitID'),
                'readertypeID'=>input('readertypeID'),
                'borrowstatus'=>'1',
                'fbooknumber'=>'0',
                'cbooknumber'=>'0',
                'booknumber'=>'0',
                'role'=>'reader',
            ];

            $res = model('User')->register($data);
            if ($res == 1) {
                $this->success('注册成功！', 'admin/user/login');
            } else {
                $this->error($res,'admin/user/register');
            }
        return ;
    }
    //注销
    public function out()
    {
        session(null);
        $this->success('成功退出！','admin/user/login');
    }
}