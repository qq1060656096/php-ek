<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 11:06
 */

namespace Zwei\ek;


class Event
{
    /**
     * 心跳事件
     */
    const EVENT_HEART = 'EVENT_HEART';

    /**
     * @var string 事件id
     */
    protected $id;
    /**
     * @var string 事件名
     */
    protected $name;
    /**
     * @var string 事件key
     */
    protected $key;
    /**
     * @var string 版本
     */
    protected $version;
    /**
     * @var string ip地址
     */
    protected $ip;
    /**
     * @var integer 时间戳
     */
    protected $time;
    /**
     * @var mixed 数据
     */
    protected $data;

    /**
     * @var mixed 附加信息
     */
    protected $additional;

    /**
     * 创建事件
     *
     * @param string $name 事件名
     * @param mixed $data 数据
     * @param null|string $key 默认没有key
     * @param null|string $version 版本默认v0
     * @param null|string $ip ip地址默认会自动获取
     * @return Event
     */
    public function NewEvent($name, $data, $key = null, $version = null, $ip = null)
    {
        $id = $this->generateEventId($ip);
        $additional = null;
        return $this->NewEventRaw($id, $name, $key, $version, $ip, $data, $additional);
    }

    /**
     * 创建事件
     *
     * @param string $id
     * @param string $name
     * @param string $key
     * @param string $version
     * @param string $ip
     * @param integer $time
     * @param mixed $data
     * @param mixed $additional
     * @return Event
     */
    public function NewEventRaw($id, $name, $key, $version, $ip, $time, $data, $additional)
    {
        $obj = new Event();
        $obj->id = $id;
        $obj->name = $name;
        $obj->key = $key;
        $obj->version = $version;
        $this->ip = $ip;
        $obj->time = $time;
        $obj->data = $data;
        $obj->additional = $additional;
        return $obj;
    }

    /**
     * 生成时间id
     * @param $ip
     * @return string
     */
    public function generateEventId($ip)
    {
        list($usec, $sec) = explode(" ", microtime());
	    return sprintf("%d-%d-%d-%d-%s", $usec, $sec, getmypid(), rand(0, 0xFFFFFFFF), $ip);
    }

    /**
     * Event转string
     * @return false|string
     */
    public function __toString()
    {
        $arr = [
            'id'    => $this->id,
            'name'  => $this->time,
            'key'   => $this->key,
            'v'     => $this->version,
            'ip'    => $this->ip,
            'time'  => $this->time,
            'data'  => $this->data,
            'addit' => $this->additional,
        ];
        return json_encode($arr);
    }


    /**
     * 字符串解析成事件对象
     * @param string $string
     * @return Event
     */
    public static function parse($string)
    {
        $arr = json_decode($string, true);
        return self::parseArray($arr);
    }

    /**
     * 数组解析成事件对象
     *
     * @param array $array
     * @return Event
     */
    public static function parseArray($array)
    {
        $event          = new Event();
        $event->id      = $array['id'];
        $event->time    = $array['time'];
        $event->key     = $array['key'];
        $event->version = $array['version'];
        $event->ip      = $array['ip'];
        $event->time    = $array['time'];
        $event->data    = $array['data'];
        $event->additional = $array['additional'];
        return $event;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * 设置事件名
     * @param string $oldName
     * @param string $newName
     */
    public function setName($oldName, $newName)
    {
        if ($this->getName() === $oldName) {
            $this->name = $newName;
        }
    }



    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getAdditional()
    {
        return $this->additional;
    }

}