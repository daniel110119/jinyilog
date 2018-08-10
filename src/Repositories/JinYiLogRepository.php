<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:51
 */

namespace Jinyi\Jinyilog\Repositories;

use Jinyi\Jinyilog\Models\JinYiLog;
use Jinyi\Jinyilog\Services\JinYiLogServices;


class JinYiLogRepository
{
    public function createActionLog($type,$content,$userinfo)
    {

        if(array_key_exists('HTTP_USER_AGENT', $_SERVER)){
            $actionLog = new JinYiLog();
            $actionLog->uid = $userinfo['uid'];
            $actionLog->username = $userinfo['username'];

            $actionLog->browser = JinYiLogServices::getBrowser($_SERVER['HTTP_USER_AGENT'],true);
            $actionLog->system = JinYiLogServices::getPlatForm($_SERVER['HTTP_USER_AGENT'],true);
            $actionLog->url = request()->getRequestUri();
            $actionLog->ip = request()->getClientIp();
            $actionLog->type = $type;
            $actionLog->content = $content;
            $res = $actionLog->save();
            return $res;
        }
    }
}