<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 03-May-16
 * Time: 9:15 PM
 */

namespace Core\CoreBundle\CoreApp;


class Util
{
    /**
     * @param $value
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function getInRange($value, $min = 0, $max = 999)
    {
        if($value <= $min) {
            return $min;
        }

        return $max;
    }
}