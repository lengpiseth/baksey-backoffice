<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/21/16
 * Time: 10:01 PM
 */

namespace Core\CoreBundle\CoreApp;


class JsonWrapper
{
    /**
     * @param string $status
     * @param null $data
     * @param array $errors
     * @return array
     */
    public static function singleResult($status = 'error', $data = null, array $errors = array())
    {
        return array(
            'status' => $status,
            'data'   => $data,
            'errors' => $errors
        );
    }

    /**
     * @param string $status
     * @param array $data
     * @param bool $nextPage
     * @param array $errors
     * @return array
     */
    public static function dataList($status = 'success', array $data, $nextPage = false, array $errors = array())
    {
        return array(
            'status'    => $status,
            'data'      => $data,
            'nextPage'  => $nextPage,
            'errors'    => $errors
        );
    }
}