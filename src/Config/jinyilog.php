<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:44
 */

return [
    'model'=>[
        'Users'=>'\App\Models\Users',  #模型
    ],
    'config'=>[
        'header'=>[
            'name'=>'Authorization',  #对应无状态中header中认证的 加密字段
            'parameter'=>[            #对应插入的加密内容中的key
                'uid'=>'uuid',
                'username'=>'username'
            ]
        ],
        'default'=> [
            'uid'=>0,          #'默认插入uid内容'
            'username'=>'访客' # 默认插入username内容
        ]
    ],
];