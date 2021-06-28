<?php

namespace app\common\validate;

use think\Validate;

class Borrow extends Validate{
    //规则
    protected  $rule = [
        'userid|用户ID' => 'require',
        'bookID|书本ID' => 'require',
        'borrowingtime|借书时间' => 'require',
        'returntime|还书时间' => 'require',
        'renewstatus|是否续借' => 'require',
        'returnstatus|是否归还' => 'require',
    ];

    //场景
    protected $scene = [
        'editborrow'=>[
            'userid','bookID','borrowingtime','returntime','renewstatus','returnstatus',
        ],
    ];
}
