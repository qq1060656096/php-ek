<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 11:42
 */

namespace Zwei\ek;

/**
 * 事件生产者
 *
 * Class EventProducers
 * @package Zwei\ek
 */
class EventProducers
{
    use EventClustersTrait;
    use EventProducersTrait;
    /**
     * @var Producers
     */
    protected $producers;

    /**
     * @var Clusters
     */
	protected $clusters;

    protected $clustersConfig;

    protected $producersConfig;
    /**
     * 构造方法初始化
     *
     * @param array $clustersConfig
     * @param array $producersConfig
     */
    public function __construct(array $clustersConfig, array $producersConfig)
    {
        $this->clustersConfig = $clustersConfig;
        $this->producersConfig = $producersConfig;
        $this->clusters = $this->getClustersFromConfig($clustersConfig);
        $this->producers = $this->getProducersFromConfig($producersConfig);
    }

    /**
     * 发送同步事件(堵塞)
     * @param string $producerName 生产者名
     * @param Event $event
     */
    public function sendSyncEvent($producerName, Event $event)
    {
        $this->sendEvent($producerName, $event, -1);
    }

    /**
     * 异步发送事件(非堵塞)
     *
     * @param string $producerName 生产者名
     * @param Event $event
     */
    public function sendAsyncEvent($producerName, Event $event)
    {
        $this->sendEvent($producerName, $event, 0);
    }

    /**
     * 发送事件(支持堵塞和非堵塞方式),默认堵塞
     * @param string $producerName 生产者名
     * @param Event $event
     * @param int $milliseconds 毫秒[-1->默认堵塞, 0->非堵塞, 大于0->堵塞多少毫秒]
     */
    public function sendEvent($producerName, Event $event, $milliseconds = -1)
    {
        return $this->sendMessage($producerName, (string)$event, $event->getKey(), $milliseconds);
    }

    /**
     * 发送kafka消息
     *
     * @param string $producerName 生产者名
     * @param string $message 消息
     * @param int $milliseconds
     * @param null $key
     */
    public function sendMessage($producerName, $message, $key = null, $milliseconds = -1)
    {
        $producer = $this->producers->get($producerName);
        $cluster = $this->clusters->get($producer->getClusterName());
        $producer->setCluster($cluster);
        $producer->sendMessage($message, $key, $milliseconds);
    }
}