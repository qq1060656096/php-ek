<?php
namespace Zwei\ek;
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 11:06
 */

use RdKafka\Producer as RdKafkaProducer;
use RdKafka\ProducerTopic as RdKafkaProducerTopic;
use RdKafka\Conf as RdKafkaConf;

class Producer
{
    protected $name;
    protected $clusterName;
    protected $topicName;
    protected $options;
    /**
     * @var Cluster
     */
    protected $cluster;

    /**
     * @var \RdKafka\Producer
     */
    protected $rdKafkaProducer = null;

    /**
     * 缓存发送的rdKafkaTopic
     * @var \RdKafka\ProducerTopic
     */
    protected $cacheRdKafkaProducerTopic = null;


    /**
     * 构造方法初始化
     * @param string $name
     * @param string $clusterName
     * @param string $topicName
     * @param array $options
     */
    public function __construct($name, $clusterName, $topicName, array $options)
    {
        $this->name = $name;
        $this->clusterName = $clusterName;
        $this->topicName = $topicName;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClusterName()
    {
        return $this->clusterName;
    }

    /**
     * @return string
     */
    public function getTopicName()
    {
        return $this->topicName;
    }

    /**
     * @return Cluster
     */
    public function getCluster()
    {
        return $this->cluster;
    }

    /**
     * @param Cluster $cluster
     */
    public function setCluster(Cluster $cluster)
    {
        $this->clusterName = $cluster->getName();
        $this->cluster = $cluster;
    }

    /**
     * @return RdKafkaProducer
     */
    public function getRdKafkaProducer()
    {
        if ($this->rdKafkaProducer) {
            return $this->rdKafkaProducer;
        }
        $this->rdKafkaProducer = $this->getNewRdKafkaProducer();
        return $this->rdKafkaProducer;
    }

    /**
     * @return RdKafkaProducer
     */
    public function getNewRdKafkaProducer()
    {
        $cluster = $this->getCluster();
        $brokerListStr = $cluster->getAddrsToString();
        $rdKafkaProducer = new RdKafkaProducer();
        $rdKafkaProducer->setLogLevel(LOG_DEBUG);
        $rdKafkaProducer->addBrokers($brokerListStr);
        return $rdKafkaProducer;
    }

    /**
     * @return RdKafkaProducerTopic
     */
    public function getRdKafkaProducerTopic()
    {
        return $this->getCacheRdKafkaProducerTopic($this->topicName);
    }

    /**
     * 获取RdKafkaProducerTopic,没有就创建,有就从缓存中获取
     * @param string $topicName
     * @return RdKafkaProducerTopic
     */
    protected function getCacheRdKafkaProducerTopic($topicName)
    {
        if (!isset($this->cacheRdKafkaProducerTopic[$topicName])) {
            $this->getRdKafkaProducer();
            $this->cacheRdKafkaProducerTopic[$topicName] = $this->getNewRdKafkaProducerTopic($topicName);
        }
        return $this->cacheRdKafkaProducerTopic[$topicName];
    }


    /**
     * @param  string $topicName 主题
     * @return \RdKafka\ProducerTopic
     */
    public function getNewRdKafkaProducerTopic($topicName)
    {
        return $this->rdKafkaProducerTopic->newTopic($topicName);
    }

    /**
     * 发送kafka消息
     *
     * @param string $message 消息
     * @param int $milliseconds 毫秒[-1->默认堵塞, 0->非堵塞, 大于0->堵塞多少毫秒]
     * @param null $key
     */
    public function sendMessage($message, $key = null, $milliseconds = -1)
    {
        $this->sendTopicMessage($this->topicName, $message, $key, $milliseconds);
    }

    /**
     * 发送kafka消息
     * @param string $topicName 主题名
     * @param string $message 消息
     * @param int $milliseconds 毫秒[-1->默认堵塞, 0->非堵塞, 大于0->堵塞多少毫秒]
     * @param string|null $key
     */
    public function sendTopicMessage($topicName, $message, $key = null, $milliseconds = -1)
    {
        $this->getCacheRdKafkaProducerTopic($topicName)->produce(RD_KAFKA_PARTITION_UA, 0, $message, $key);
        // 堵塞
        $this->getRdKafkaProducer()->poll($milliseconds);
    }
}