<?php
namespace Zwei\ek\Exceptions;

/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 14:39
 */

class EkBaseException extends \Exception
{
    /**
     * 异常对象转array
     * @param \Exception $e
     * @return array
     */
    public static function convertArray(\Exception $e)
    {
        return [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'traceAsString' => $e->getTraceAsString(),
        ];
    }
}