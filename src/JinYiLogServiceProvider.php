<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:40
 */

namespace Jinyi\Jinyilog;

use Jinyi\Jinyilog\Models\JinYiLog;
use Jinyi\Jinyilog\Repositories\JinYiLogRepository;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\ServiceProvider;

class JinYiLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {

        $header =config("actionlog.config.header");
        $data = $request->header($header['name']);
        if($data){
            $userinfo = json_decode(decrypt($data),true);
            $userinfo = collect(config("actionlog.config.header.parameter"))
                ->map(function($item) use($userinfo){
                    return $userinfo[$item];
                });
        }else{
            $userinfo = config("actionlog.config.default");
        }

        // Publish configuration files
        $this->publishes([
            __DIR__ . '/Migrations' => database_path('migrations'),
        ], 'migrations');


        $this->publishes([
            __DIR__.'/config/actionlog.php' => config_path('actionlog.php'),
        ], 'config');

        $model = config("actionlog.model");
        if($model){
            foreach($model as $k => $v) {
                $v::updated(function($data) use($k,$userinfo){
                    JinYiLog::createActionLog($k.' (UPDATE) ',json_encode($data),$userinfo);
                });
                $v::saved(function($data) use($k,$userinfo){
                    JinYiLog::createActionLog($k.' (ADD)',json_encode($data),$userinfo);
                });
                $v::deleted(function($data) use($k,$userinfo){
                    JinYiLog::createActionLog($k.' (DELETE) ',json_encode($data),$userinfo);
                });
            }
        }


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("ActionLog",function($app){
            return new JinYiLogRepository();
        });
    }
}