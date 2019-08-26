<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:13
 */

namespace Zwei\ek;


/**
 * 消费者列表
 * Class Consumers
 * @package Zwei\ek
 */
class Consumers
{
    protected $consumers = [];

    /**
     * 设置单个消费者
     * @param ConsumerAbstract $consumer
     */
    public function set(ConsumerAbstract $consumer)
    {
        $this->consumers[$consumer->getConsumerConfig()->getName()] = $consumer;
    }

    /**
     * 设置所有消费者
     * @param array $consumers
     */
    public function setAll($consumers)
    {
        $this->consumers = [];
        foreach ($consumers as $consumer) {
            $this->set($consumer);
        }
    }

    /**
     * 获取所有消费者
     * @return array
     */
    public function getAll()
    {
        return $this->consumers;
    }

    /**
     * 获取单个消费者
     * @param string $name
     * @return ConsumerAbstract
     */
    public function get($name)
    {
        if (!isset($this->consumers[$name])) {

        }
        return $this->consumers[$name];
    }
}