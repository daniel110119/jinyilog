<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:45
 */

namespace Jinyi\Jinyilog\Facades;

use Illuminate\Support\Facades\Facade;

class JinYiLogFacade extends Facade
{
    protected static function getFacadeAccessor(){
        return 'JinYiLog';
    }
}