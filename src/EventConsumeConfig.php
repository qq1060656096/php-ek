<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:32
 */

namespace Zwei\ek;


use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use Zwei\ek\Exceptions\ConsumerParamException;

/**
 * 事件消费配置
 *
 * Class EventConsumeConfig
 * @package Zwei\ek
 */
class EventConsumeConfig
{
    /**
     * @var string 事件名
     */
    protected $eventName;

    /**
     * @var callable 事件消费处理函数
     */
    protected $callback;

    /**
     * EventConsumeConfig constructor.
     * @param string $eventName
     * @param $callback
     */
    public function __construct($eventName, $callback)
    {
        $this->eventName = $eventName;
        $this->callback = $callback;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

}
