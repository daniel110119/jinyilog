<?php
/**
 * Created by PhpStorm.
 * User: LuGeGe
 * Date: 2018/8/10
 * Time: 11:53
 */

namespace Jinyi\Jinyilog\Services;

use Illuminate\Http\Request;


class JinYiLogServices
{
    protected static $browsers = array(
        'Edge'            => 'Edge',
        'IE'              => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Chrome'          => '(?:\bCrMo\b|CriOS|Android)?.*Chrome/[.0-9]* (Mobile)?',
        'Opera'           => 'Opera.*Mini|Opera.*Mobi|Android.*Opera|Mobile.*OPR/[0-9.]+|Coast/[0-9.]+',
        'Firefox'         => 'fennec|firefox.*maemo|(Mobile|Tablet).*Firefox|Firefox.*Mobile',
        'Safari'          => 'Version.*Mobile.*Safari|Safari.*Mobile|MobileSafari',
        'UCBrowser'       => 'UC.*Browser|UCWEB', //UC游览器
        'QQBrowser'       => 'MQQBrowser|TencentTraveler', //QQ游览器
        'The world'       => 'The world', //世界之窗游览器
        'Maxthon'         => 'Maxthon', //遨游游览器
        'baiduboxapp'     => 'baiduboxapp',
        'baidubrowser'    => 'baidubrowser',
        'NokiaBrowser'    => 'Nokia',
    );


    protected static $platforms = array(
        'iOS'               => '\biPhone.*Mobile|\biPod|\biPad',
        'Windows Mobile OS' => 'Windows CE.*(PPC|Smartphone|Mobile|[0-9]{3}x[0-9]{3})|Window Mobile|Windows Phone [0-9.]+|WCE;',
        'Windows Phone OS'  => 'Windows Phone 10.0|Windows Phone 8.1|Windows Phone 8.0|Windows Phone OS|XBLWP7|ZuneWP7|Windows NT 6.[23]; ARM;',
        'Android'           => 'Android',
        'BlackBerry OS'     => 'blackberry|\bBB10\b|rim tablet os', //黑莓
        'SymbianOS'         => 'Symbian|SymbOS|Series60|Series40|SYB-[0-9]+|\bS60\b', //塞班
        'webOS'             => 'webOS|hpwOS',
        'MicroMessenger'    => 'MicroMessenger', //微信
        'Windows'           => 'Windows',
        'Windows NT'        => 'Windows NT',
        'Mac OS X'          => 'Mac OS X',
        'Ubuntu'            => 'Ubuntu',
        'Linux'             => 'Linux',
        'Chrome OS'         => 'CrOS',
    );


    protected static $versionRegexs = array(

        // Browser
        'Maxthon'       => 'Maxthon [VER]', //遨游
        'Chrome'        => array('Chrome/[VER]', 'CriOS/[VER]', 'CrMo/[VER]'), //谷歌
        'Firefox'       => 'Firefox/[VER]', //火狐
        'Fennec'        => 'Fennec/[VER]', //火狐
        'IE'            => array('IEMobile/[VER];', 'IEMobile [VER]', 'MSIE [VER];', 'rv:[VER]'),
        'Opera'         => array('OPR/[VER]', 'Opera Mini/[VER]', 'Version/[VER]', 'Opera [VER]'),
        'UC Browser'    => 'UC Browser[VER]', //UC
        'QQBrowser'     => array('MQQBrowser/[VER]','TencentTraveler/[VER]'), //QQ
        'MicroMessenger'=> 'MicroMessenger/[VER]', //微信
        'baiduboxapp'   => 'baiduboxapp/[VER]', //百度盒子
        'baidubrowser'  => 'baidubrowser/[VER]', //百度
        'Safari'        => array('Version/[VER]', 'Safari/[VER]' ), //Mac OS X中的浏览器
        'NokiaBrowser'  => 'NokiaBrowser/[VER]', //诺基亚

        // OS
        'iOS'              => '\bi?OS\b [VER][ ;]{1}',
        'BlackBerry'       => array('BlackBerry[\w]+/[VER]', 'BlackBerry.*Version/[VER]', 'Version/[VER]'), //黑莓手机
        'Windows Phone OS' => array( 'Windows Phone OS [VER]', 'Windows Phone [VER]'),
        'Windows Phone'    => 'Windows Phone [VER]',
        'Windows NT'       => 'Windows NT [VER]',
        'Windows'          => 'Windows NT [VER]',
        'SymbianOS'        => array('SymbianOS/[VER]', 'Symbian/[VER]'), //塞班系统
        'webOS'            => array('webOS/[VER]', 'hpwOS/[VER];'), //LG
        'Mac OS X'         => 'MAC OS X [VER]', //苹果系统
        'BlackBerry OS'    => array('BlackBerry[\w]+/[VER]', 'BlackBerry.*Version/[VER]', 'Version/[VER]'),
        'Android'          => 'Android [VER]',
        'Chrome OS'        => 'CrOS x86_64 [VER]',
    );



    public static function getBrowser($userAgent,$isReTurnVersion = false) {
        if(empty($userAgent)) {
            return '';
        }
        $clientBrowser = '';
        foreach((array)self::$browsers as $key => $browser) {
            if(self::match($browser,$userAgent)) {
                $clientBrowser = $key;
                break;
            }
        }
        if($isReTurnVersion && $clientBrowser) {
            $clientBrowser .= ' '.self::getVersion($clientBrowser,$userAgent);
        }
        return $clientBrowser;
    }


    public static function getPlatForm($userAgent,$isReTurnVersion = false) {
        if(empty($userAgent)) {
            return '';
        }
        $clientPlatform = '';
        foreach((array)self::$platforms as $key => $platform) {
            if(self::match($platform,$userAgent)) {
                $clientPlatform = $key;
                break;
            }
        }
        if($isReTurnVersion && $clientPlatform) {
            $clientPlatform .= ' '.self::getVersion($clientPlatform,$userAgent);
        }
        return $clientPlatform;
    }


    public static function getVersion($propertyName,$userAgent) {

        $verRegex = array_key_exists($propertyName,self::$versionRegexs)
            ? self::$versionRegexs[$propertyName] : null;
        if(!$verRegex) {
            return '';
        } else {
            $verRegex = (array)$verRegex;
        }

        $match = self::matchVersion($verRegex,$userAgent); //开始匹配
        if($match && stripos($propertyName,'window') !== false) { //windown系统版本号需要转换
            return self::getWinVersion($match);
        } else {
            return str_replace('_','.',$match);
        }
    }


    protected static function getWinVersion($match) {
        if($match == '6.0') {
            return 'Vista';
        }
        else if($match == '6.1') {
            return '7';
        }
        else if($match == '6.2') {
            return '8';
        }
        else if($match == '5.1') {
            return 'XP';
        }
    }


    protected static function match($regex,$userAgent) {
        return (bool)preg_match(sprintf('#%s#is',$regex),$userAgent,$matches);
    }


    protected static function matchVersion($regexs,$userAgent) {
        foreach((array)$regexs as $regex) {
            $regex = str_replace('[VER]','([\w\.]+)', $regex);
            $match = (bool)preg_match(sprintf('#%s#is',$regex),$userAgent,$matches);
            if($match) {
                return $matches[1];
            }
        }
        return '';
    }

}