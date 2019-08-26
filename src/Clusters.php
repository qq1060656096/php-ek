<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 14:36
 */

namespace Zwei\ek;

/**
 * 集群
 *
 * Class Clusters
 * @package Zwei\ek
 */
class Clusters
{
    protected $clusters = [];

    /**
     * 设置单个集群
     * @param Cluster $cluster
     */
    public function set(Cluster $cluster)
    {
        $this->clusters[$cluster->getName()] = $cluster;
    }

    /**
     * 设置所有集群
     * @param array $clusters
     */
    public function setAll($clusters)
    {
        $this->clusters = [];
        foreach ($clusters as $cluster) {
            $this->set($cluster);
        }
    }

    /**
     * 获取所有集群
     * @return array
     */
    public function getAll()
    {
        return $this->clusters;
    }

    /**
     * 获取集群
     * @param string $name
     * @return Clusters
     */
    public function get($name)
    {
        if (!isset($this->clusters[$name])) {

        }
        return $this->clusters[$name];
    }
}