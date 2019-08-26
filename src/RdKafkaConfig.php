<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-21
 * Time: 18:19
 */

namespace Zwei\ek;


use RdKafka\Conf;

class RdKafkaConfig
{
    /**
     * @param array $options
     * @return Conf
     */
    public function getNewConf(array $options)
    {
        $conf = new Conf();
        $conf = $this->setConf($conf, $options);
        return $conf;
    }

    /**
     * 设置RdKafka配置
     * @param Conf $conf 配置实例
     * @param array $options 选项键值数组
     * @return \RdKafka\Conf
     */
    public function setConf(Conf $conf, array $options)
    {
        foreach ($options as $key => $value) {
            $conf->set($key, $value);
        }
        return $conf;
    }
}