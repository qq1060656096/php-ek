<?php
namespace Zwei\ek\Exceptions;

/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 14:39
 */

/**
 * Class ConsumerParamException
 * @package Zwei\ek\Exceptions
 */
class ConsumerParamException extends ParamException
{
    /**
     * 消费者type类型错误
     * @param string $type
     * @throws ConsumerParamException
     */
    public static function typeError($type)
    {
        throw new ConsumerParamException("consumer.params.typeError: $type");
    }
}