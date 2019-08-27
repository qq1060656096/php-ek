<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-27
 * Time: 18:42
 */

namespace Zwei\ek\Exceptions;


class EventConsumeResultParamException extends ParamException
{
    public static function status()
    {
        throw new EventConsumeResultParamException('consumer.event.EventConsumeResult.status.error');
    }
}