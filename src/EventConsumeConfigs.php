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
use Zwei\ek\Exceptions\ConsumerEventConfigNotFoundException;
use Zwei\ek\Exceptions\ConsumerParamException;

/**
 * 事件消费配置
 *
 * Class EventConsumeConfigs
 * @package Zwei\ek
 */
class EventConsumeConfigs
{
    protected $eventConsumeConfigs = [];

    /**
     * 设置单个生产者
     * @param EventConsumeConfig $eventConsumeConfig
     */
    public function set(EventConsumeConfig $eventConsumeConfig)
    {
        $this->eventConsumeConfigs[$eventConsumeConfig->getEventName()] = $eventConsumeConfig;
    }

    /**
     * 设置所有事件消费配置
     * @param array $eventConsumeConfigs
     */
    public function setAll($eventConsumeConfigs)
    {
        $this->eventConsumeConfigs = [];
        foreach ($eventConsumeConfigs as $eventConsumeConfig) {
            $this->set($eventConsumeConfig);
        }
    }

    /**
     * 获取所有消费事件配置
     * @return array
     */
    public function getAll()
    {
        return $this->eventConsumeConfigs;
    }

    /**
     * 获取单个消费事件配置
     * @param string $eventName
     * @return EventConsumeConfig
     * @throws ConsumerEventConfigNotFoundException
     */
    public function get($eventName)
    {
        if (!isset($this->eventConsumeConfigs[$eventName])) {
            throw new ConsumerEventConfigNotFoundException('consumer.event.config.notFound');
        }
        return $this->eventConsumeConfigs[$eventName];
    }

}
