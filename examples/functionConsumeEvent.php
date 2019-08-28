<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-28
 * Time: 09:14
 */

namespace Zwei\ek\Examples {
    /**
     * 函数消费事件
     * @param \RdKafka\Message $message
     * @param \Zwei\ek\Event $event
     * @return \Zwei\ek\EventConsumeResult
     * @throws \Zwei\ek\Exceptions\EventConsumeResultParamException
     */
    function functionConsumeEvent(\RdKafka\Message $message, \Zwei\ek\Event $event)
    {
        var_dump(__FUNCTION__, $event);
        if (true) {
            // 处理成功
            return new \Zwei\ek\EventConsumeResult(true, []);
        } else {
            // 处理失败
            return new \Zwei\ek\EventConsumeResult(false, []);
        }
    }
}