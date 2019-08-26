<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:32
 */

namespace Zwei\ek;


/**
 * 消费者配置
 *
 * Class ConsumerConfig
 * @package Zwei\ek
 */
class ConsumerConfig
{
    /**
     * @var string 消费者名
     */
    protected $name;
    /**
     * @var string 集群名
     */
    protected $clusterName;

    /**
     * @var string 消费者group_id
     */
    protected $groupId;
    /**
     * @var array 消费者主题列表
     */
    protected $topicNames;
    /**
     * @var integer 消费者超时(毫秒)
     */
    protected $timeoutMs;

    protected $options;

    /**
     * 构造方法初始化
     * @param string $name
     * @param string $clusterName
     * @param string $groupId
     * @param array $topicNames
     * @param integer $timeoutMs
     * @param array $options
     */
    public function __construct($name, $clusterName, $groupId, array $topicNames, $timeoutMs, array $options)
    {
        $this->name         = $name;
        $this->clusterName  = $clusterName;
        $this->groupId      = $groupId;
        $this->topicNames   = $topicNames;
        $this->timeoutMs    = $timeoutMs;
        $this->options      = $options;
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
     * @param string $oldClusterName
     * @param string $newClusterName
     */
    public function setClusterName($oldClusterName, $newClusterName)
    {
        if ($this->clusterName === $oldClusterName) {
            $this->clusterName = $newClusterName;
        }
    }



    /**
     * @return string
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @return array
     */
    public function getTopicNames()
    {
        return $this->topicNames;
    }

    /**
     * @return int
     */
    public function getTimeoutMs()
    {
        return $this->timeoutMs;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return [
            'name'          => $this->getName(),
            'clusterName'   => $this->getClusterName(),
            'groupId'       => $this->getGroupId(),
            'topicNames'    => $this->getTopicNames(),
            'options'       => $this->getOptions(),
        ];
    }
}
