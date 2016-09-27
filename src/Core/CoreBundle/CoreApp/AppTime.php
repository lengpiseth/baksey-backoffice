<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 10-Apr-16
 * Time: 9:57 AM
 */

namespace Core\CoreBundle\CoreApp;


class AppTime
{
    public static function millisecond()
    {
        return intval(self::microsecond() * 1000);
    }

    public static function microsecond()
    {
        return round(microtime(1), 4);
    }
}