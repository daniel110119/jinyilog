#金亿
#金亿自动日志
...
#引入
    "require": {
                "jinyi/jinyilog": "dev-master",
            },
    composer require jinyi/jinyilog dev-master

...
    
- laravel>5.5 自动注册
- 迁移数据卢 php artisan migrate
- 发布配置 php artisan vendor:publish  --provider="Jinyi\Jinyilog\JinYiLogServiceProvider"
