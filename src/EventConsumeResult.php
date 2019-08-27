<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-21
 * Time: 22:50
 */

namespace Zwei\ek;

use Zwei\ek\Exceptions\EventConsumeResultParamException;

/**
 * 事件消费结果
 *
 * Class EventConsumeResult
 * @package Zwei\ek
 */
class EventConsumeResult
{
    const STATUS_SUCCESS = true;
    const STATUS_FAIL = false;
    /**
     * @var bool
     */
    protected $status;

    protected $data;

    /**
     * EventConsumeResult constructor.
     * @param bool $status 状态[true->成功, false->失败]
     * @param $data
     * @throws EventConsumeResultParamException
     */
    public function __construct($status, $data)
    {
        $this->setStatus($status);
        $this->data = $data;
    }


    /**
     * @param bool $status
     * @throws EventConsumeResultParamException
     */
    protected function setStatus($status)
    {
        if (!is_bool($status)) {
            EventConsumeResultParamException::status();
        }
        $this->status = $status;
    }


    /**
     * @return bool
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