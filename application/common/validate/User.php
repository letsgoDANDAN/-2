<?php

namespace app\common\validate;

use think\Validate;

class User extends Validate{
    //规则
    protected  $rule = [
        'userid|用户名' => 'require',
        'password|密码' => 'require',
        'captcha|验证码' => 'require|captcha',
        'name|昵称'=>'require',
        'Email|Email' =>'require',
        'phone|手机号'=>'require',
        'name|姓名'=>'require',
        'unitID|学校'=>'require',
        'readertypeID|读者类型'=>'require',
        'borrowstatus|借阅类型'=>'require',
        'fbooknumber|外文书'=>'require',
        'cboonnumber|中文书'=>'require',
        'booknumber|总数'=>'require',
    ];

    //场景
    protected $scene = [
        'login' => ['userid','password'/*,'captcha'*/],
        'update'=>['userid','name'],
         'register'=>['userid','name','password','Email','phone'],
    ];
}
