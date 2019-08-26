<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-26
 * Time: 10:29
 */

namespace Zwei\ek;

/**
 * Trait EventProducersTrait
 * @package Zwei\ek
 */
trait EventProducersTrait
{
    /**
     * 根据配置获取生产者列表
     * @param Clusters $clusters
     * @param array $producersConfig
     * @return Producers
     */
    protected function getProducersFromConfig(Clusters $clusters, array $producersConfig)
    {
        $producers = new Producers();
        foreach ($producersConfig as $key => $row) {
            $name           = $key;
            $clusterName    = $row['clusterName'];
            $topicName      = $row['topicName'];
            $options        = $row['options'];
            $rowProducer    = new Producer($name, $clusterName, $topicName, $options);
            $rowProducer->setCluster($clusters->get($clusterName));
            $producers->set($rowProducer);
        }
        return $producers;
    }
}