<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:13
 */

namespace Zwei\ek;

/**
 * 生产者列表
 * Class Producers
 * @package Zwei\ek
 */
class Producers
{
    protected $producers = [];

    /**
     * 设置单个生产者
     * @param Producer $producer
     */
    public function set(Producer $producer)
    {
        $this->producers[$producer->getName()] = $producer;
    }

    /**
     * 设置所有生产者
     * @param array $producers
     */
    public function setAll($producers)
    {
        $this->producers = [];
        foreach ($producers as $producer) {
            $this->set($producer);
        }
    }

    /**
     * 获取所有生产者
     * @return array
     */
    public function getAll()
    {
        return $this->producers;
    }

    /**
     * 获取单个生成者
     * @param string $name
     * @return Producer
     */
    public function get($name)
    {
        if (!isset($this->producers[$name])) {

        }
        return $this->producers[$name];
    }
}