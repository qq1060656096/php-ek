<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-26
 * Time: 10:29
 */

namespace Zwei\ek;

/**
 * Trait EventClustersTrait
 * @package Zwei\ek
 */
trait EventClustersTrait
{
    /**
     * 根据配置设置集群列表
     * @param array $clustersConfig
     * @return Clusters
     */
    protected function getClustersFromConfig(array $clustersConfig)
    {
        $clusters = new Clusters();
        foreach ($clustersConfig as $row) {
            $cluster = new Cluster($row['name'], $row['addrs']);
            $clusters->set($cluster);
        }
        return $clusters;
    }
}