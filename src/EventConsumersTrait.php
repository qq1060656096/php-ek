<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-26
 * Time: 10:29
 */

namespace Zwei\ek;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Trait EventConsumersTrait
 * @package Zwei\ek
 */
trait EventConsumersTrait
{

    /**
     * 根据配置获取消费者列表
     * @param Clusters $clusters
     * @param Producers $producers
     * @param array $consumersConfig
     * @return Consumers
     */
    protected function getConsumersFromConfig(Clusters $clusters, Producers $producers, array $consumersConfig)
    {
        $consumers = new Consumers();
        foreach ($consumersConfig as $key => $row) {
            $name           = $key;
            $clusterName    = $row['clusterName'];
            $groupId        = $row['groupId'];
            $topicNames     = $row['topicNames'];
            $timeoutMs      = $row['timeoutMs'];
            $options        = $row['options'];
            $producerName   = $row['producerName'];
            $consumerConfig = new ConsumerConfig($name, $clusterName, $groupId, $topicNames, $timeoutMs, $options);
            $producer       = $producers->get($producerName);
            $eventConsumeConfigs = new EventConsumeConfigs();
            $rowEvents      = $row['events'];
            foreach ($rowEvents as $keyEventName => $row2Callback) {
                $eventConsumeConfigs->set(new EventConsumeConfig($keyEventName, $row2Callback));
            }
            $rowLog         = $row['log'];
            $log            = new Logger($name);
            $log->pushHandler(new RotatingFileHandler($rowLog['fileName'], $rowLog['maxFiles']));
            $logger         = $log;
            /* @var $rowConsumer ConsumerAbstract */
            $rowConsumer    = new $row['class']($consumerConfig, $producer, $eventConsumeConfigs, $logger);
            $rowConsumer->setCluster($clusters->get($clusterName));
            $consumers->set($rowConsumer);
        }
        return $consumers;
    }
}