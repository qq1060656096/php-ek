<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 14:36
 */

namespace Zwei\ek;


class Cluster
{
    protected $name = "";

    protected $addrs = [];

    public function __construct($name, $addrs)
    {
        $this->name = $name;
        $this->addrs = $addrs;
    }

    protected function setName($name)
    {
        if (!is_string($name)) {

        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddrs()
    {
        return $this->addrs;
    }

    public function getAddrsToString()
    {
        return implode(',', $this->addrs);
    }
}