<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:32
 */

namespace Zwei\ek;


use Monolog\Logger;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Message;
use Zwei\ek\Exceptions\ConsumerParamException;

abstract class ConsumerAbstract
{
    /**
     * 消费者配置
     * @var ConsumerConfig
     */
    protected $consumerConfig;

    /**
     * @var string 消费者类型(标准类型、监听类型)
     */
    protected $type;
    /**
     * @var Logger 日志
     */
    protected $logger;
    /**
     * @var Producer
     */
    protected $producer;
    /**
     * @var EventConsumeConfigs 事件消费配置
     */
    protected $eventConsumeConfigs;
    /**
     * @var Cluster
     */
    protected $cluster;
    /**
     * @var Conf RdKafka配置
     */
    protected $rdKafkaConf;

    /**
     * @var KafkaConsumer RdKafka消费者
     */
    protected $rdKafkaConsumer;
    /**
     * 标准类型消费者
     */
    const TYPE_NORMAL = 'normal';

    /**
     * 监听类型消费者
     */
    const TYPE_LISTEN= 'listen';


    /**
     * 构造方法初始化
     * @param ConsumerConfig $consumerConfig
     * @param Producer $producer
     * @param EventConsumeConfigs $eventConsumeConfigs
     * @param Logger $logger
     * @throws ConsumerParamException
     */
    public function __construct(ConsumerConfig $consumerConfig, Producer $producer, EventConsumeConfigs $eventConsumeConfigs, Logger $logger)
    {
        $this->consumerConfig = $consumerConfig;
        $this->setType();
        $this->producer = $producer;
        $this->eventConsumeConfigs = $eventConsumeConfigs;
        $this->logger = $logger;
        $options = $this->getConsumerConfig()->getOptions();
        isset($options['offset.store.method']) ? null : $options['offset.store.method'] = 'broker';
        $RdKafkaConfig = new RdKafkaConfig();
        $this->rdKafkaConf = $RdKafkaConfig->getNewConf($options);
    }

    /**
     * @return ConsumerConfig
     */
    public function getConsumerConfig()
    {
        return $this->consumerConfig;
    }


    /**
     * 设置消费者类型
     *
     * @throws ConsumerParamException
     */
    protected function setType()
    {
        switch (true) {
            case stripos($this->getConsumerConfig()->getName(), self::TYPE_NORMAL) === 0:
                $this->type = self::TYPE_NORMAL;
                break;
            case stripos($this->getConsumerConfig()->getName(), self::TYPE_LISTEN) === 0:
                $this->type = self::TYPE_LISTEN;
                break;
            default:
                throw new ConsumerParamException();
                break;
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return EventConsumeConfigs
     */
    public function getEventConsumeConfigs()
    {
        return $this->eventConsumeConfigs;
    }

    /**
     * @param array $eventConsumeConfigs
     * @throws ConsumerParamException
     */
    protected function setEventConsumeConfigs(array $eventConsumeConfigs)
    {
        foreach ($eventConsumeConfigs as $row) {
            if (!($row instanceof EventConsumeConfig)) {
                throw new ConsumerParamException();
            }
        }
        $this->eventConsumeConfigs = $eventConsumeConfigs;
    }


    /**
     * @param Cluster $cluster
     */
    public function setCluster(Cluster $cluster)
    {
        $this->getConsumerConfig()->setClusterName($this->getConsumerConfig()->getClusterName(), $cluster->getName());
        $this->cluster = $cluster;
        $this->producer->setCluster($cluster);
    }

    /**
     * 设置必要的kafka配置
     */
    protected function setRequiredRdKafkaConfig()
    {
        $this->rdKafkaConf->set('group.id', $this->consumerConfig->getGroupId());
        $this->rdKafkaConf->set('metadata.broker.list', $this->cluster->getAddrsToString());
        $this->rebalance();
    }

    /**
     * 获取消费者
     *
     * @return KafkaConsumer
     */
    public function getRdKafkaConsumer()
    {
        if ($this->rdKafkaConsumer) {
            return $this->rdKafkaConsumer;
        }
        $this->setRequiredRdKafkaConfig();
        $this->rdKafkaConsumer = $this->getNewRdKafkaConsumer($this->rdKafkaConf, $this->getConsumerConfig()->getTopicNames());
        return $this->rdKafkaConsumer;
    }

    /**
     * 获取新的消费者
     *
     * @param Conf $config kafka配置
     * @param array $topicList 主题
     * @return KafkaConsumer
     */
    public function getNewRdKafkaConsumer(Conf $config, array $topicList)
    {
        $consumer = new KafkaConsumer($config);
        $consumer->subscribe($topicList);
        return $consumer;
    }

    /**
     * 运行消息消费
     * @return mixed
     */
    public abstract function runConsume();

    /**
     * 消费消息
     * @param Message $message
     * @return mixed
     */
    public abstract function consume(Message $message);

    /**
     * 再均衡
     */
    public function rebalance()
    {
        $consumer = $this;
        $this->rdKafkaConf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) use ($consumer) {
            $partitionsVar = Helper::varDump($partitions);
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    $consumer->logger->info("consumer.rdKafka.rebalance.assign", [$partitionsVar]);
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    $consumer->logger->info("consumer.rdKafka.rebalance.revoke", [$partitionsVar]);
                    $kafka->assign(NULL);
                    break;
                default:
                    $this->logger->error("consumer.rdKafka.rebalance.err", [$partitionsVar]);
                    throw new \Exception($err);
            }
        });
    }
}
