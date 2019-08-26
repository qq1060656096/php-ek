<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-21
 * Time: 22:50
 */

namespace Zwei\ek;

/**
 * 事件消费结果
 *
 * Class EventConsumeResult
 * @package Zwei\ek
 */
class EventConsumeResult
{
    protected $status;
    protected $data;

    public function __construct($status, $data)
    {
        $this->status = $status;
        $this->data = $data;
    }
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }/**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}