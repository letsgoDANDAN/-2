<?php

namespace app\common\validate;

use think\Validate;

class Book extends Validate{
    //规则
    protected  $rule = [
        'bookID|书本ID' => 'require',
        'ISBN|图书ISBN' => 'require',
        'branchID|所属馆' => 'require',
        'readerstatus|借阅状态' => 'require',
        'details|详情' => 'require',
    ];

    //场景
    protected $scene = [
        'addbookdescirbe'=>[
            'bookID','ISBN','branchID','readerstatus','details'
        ],
        'editbookdescirbe'=>[
            'sonID','publicID','borrowtypeID','bookname','price','introduce','picture'
        ],
    ];
}
