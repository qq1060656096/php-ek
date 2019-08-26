<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 11:42
 */

namespace Zwei\ek;

/**
 * 事件消费者
 *
 * Class EventConsumers
 * @package Zwei\ek
 */
class EventConsumers
{
    use EventClustersTrait;
    use EventProducersTrait;
    use EventConsumersTrait;
    /**
     * @var Clusters
     */
	protected $clusters;

    /**
     * @var Producers
     */
    protected $producers;

    /**
     * @var Consumers
     */
    protected $consumers;

    protected $clustersConfig;

    protected $consumersConfig;

    protected $producersConfig;

    /**
     * EventConsumers constructor.
     *
     * @param array $clustersConfig
     * @param array $consumersConfig
     * @param array $producersConfig
     */
    public function __construct(array $clustersConfig, array $consumersConfig, array $producersConfig)
    {
        $this->clustersConfig = $clustersConfig;
        $this->consumersConfig = $consumersConfig;
        $this->producersConfig = $producersConfig;
        $this->init();
    }

    /**
     * 初始化实例
     */
    protected function init()
    {
        $this->clusters = $this->getClustersFromConfig($this->clustersConfig);
        $this->producers = $this->getProducersFromConfig($this->clusters, $this->producersConfig);
        $this->consumers = $this->getConsumersFromConfig($this->clusters, $this->producers, $this->consumersConfig);
    }


    /**
     * 设置
     * @param array $clustersConfig
     * @return Clusters
     */
    protected function set(array $clustersConfig)
    {
        $clusters = new Clusters();
        foreach ($clustersConfig as $row) {
            $cluster = new Cluster($row['name'], $row['addrs']);
            $clusters->set($cluster);
        }
        return $clusters;
    }

    /**
     * 运行消费消息
     * @param string $consumerName 消费者名字
     */
    public function runConsume($consumerName)
    {
        $consumer = $this->consumers->get($consumerName);
        $cluster = $this->clusters->get($consumer->getConsumerConfig()->getClusterName());
        $consumer->setCluster($cluster);
        $consumer->runConsume();
    }
}