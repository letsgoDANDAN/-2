<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/26
 * Time: 23:54
 */

namespace app\common\validate;


use think\Validate;

class Reader extends Validate
{
    protected  $rule = [
        'userid|用户名' => 'require',
        'password|密码' => 'require',
        'name|昵称'=>'require',
        'Email|Email'=>'require',
        'phone|手机号'=>'require',
    ];

    //场景
    protected $scene = [
        'update'=>['userid','name','Email','phone']
    ];
}