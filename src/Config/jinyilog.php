<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:44
 */

return [
    'model'=>[
        #模型 'Users'=>'\App\Models\Users',
    ],
    'config'=>[
        'header'=>[
            'name'=>'Authorization',  #对应无状态中header中认证的 加密字段
            'parameter'=>[            #对应插入的加密内容中的key
                'uid'=>'你对应的字段',
                'username'=>'你对应的名字'
            ]
        ],
        'default'=> [
            'uid'=>0,          #'默认插入uid内容'
            'username'=>'访客' # 默认插入username内容
        ]
    ],
];