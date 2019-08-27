<?php
namespace Zwei\ek;
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 11:06
 */

use Monolog\Logger;
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
     * @var Conf RdKafka配置
     */
    protected $rdKafkaConf;

    /**
     * @var Logger 日志
     */
    protected $logger;

    /**
     * 构造方法初始化
     * @param string $name
     * @param string $clusterName
     * @param string $topicName
     * @param array $options
     */
    public function __construct($name, $clusterName, $topicName, array $options, Logger $logger)
    {
        $this->name = $name;
        $this->clusterName = $clusterName;
        $this->topicName = $topicName;
        $this->options = $options;
        $this->logger = $logger;
        $rdKafkaConfig = new RdKafkaConfig();
        $this->rdKafkaConf = $rdKafkaConfig->getNewConf($options);
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
        $this->setRequiredRdKafkaConfig();
        $cluster = $this->getCluster();
        $brokerListStr = $cluster->getAddrsToString();
        $rdKafkaProducer = new RdKafkaProducer($this->rdKafkaConf);
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
        return $this->getRdKafkaProducer()->newTopic($topicName);
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

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * 设置必要的kafka配置
     */
    protected function setRequiredRdKafkaConfig()
    {
        $producer = $this;
        // 发送回调
        $this->rdKafkaConf->setDrMsgCb(function ($kafka, $message) use ($producer) {
            if ($message->err) {
                // 消息发送失败
                // message permanently failed to be delivered
                $producer->getLogger()->error("producer.setDrMsgCb.report", [
                    '$kafka' => Helper::varDump($kafka),
                    '$message' => Helper::varDump($message),
                ]);
            } else {
                // message successfully delivered
            }
        });
        // 错误回调
        $this->rdKafkaConf->setErrorCb(function ($kafka, $err, $reason) use ($producer) {
            $producer->getLogger()->error("producer.setErrorCb.callback", [
                '$kafka' => Helper::varDump($kafka),
                '$err' => Helper::varDump($err),
                '$errStr' => rd_kafka_err2str($err),
                '$reason' => Helper::varDump($reason),
            ]);
        });
    }

}