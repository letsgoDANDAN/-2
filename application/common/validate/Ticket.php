<?php

namespace app\common\validate;

use think\Validate;

class Ticket extends Validate{
    //规则
    protected  $rule = [
        'fineid|罚单号' => 'require',
        'finetypeID|罚单类型' => 'require',
        'userid|用户ID' => 'require',
        'totalfineprice|罚金' => 'require',
        'finedetail|详情' => 'require',
        'settlestatus|是否处理' => 'require'
    ];

    //场景
    protected $scene = [

        'editticket'=>[
            'finetypeID','totalfineprice','finedetail','settlestatus'
        ],
    ];
}
