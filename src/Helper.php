<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-27
 * Time: 10:40
 */

namespace Zwei\ek;


class Helper
{
    /**
     * @param $var
     * @return false|string
     */
    public static function varDump($var){
        ob_start();
        var_dump($var);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
}