<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:49
 */

namespace Jinyi\Jinyilog\Models;
use Illuminate\Database\Eloquent\Model;

class JinYiLog extends Model
{
    protected $table = "jinyi_log";
    protected $fillable = ['uid','username','type','ip','content'];
}