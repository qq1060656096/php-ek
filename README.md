# php-ek

=====

介绍
----

ek(Event Kafka)是一个基于事件消费的kafka消费者和生产者, 你可以轻松使用示例和配置开发你的应用

开发文档
=====

|  名称     | 多集群   | 跨集群转发事件 | 阿里云kafka | 同步 | 异步 | 日志分割 | 手动提交|
| -------  |:--------:| ------------| ----------- | --- | ---- | ------- | ------ |
| 生产者    | 支持     |              | 支持        | 支持 | 支持 | 支持    |     |
| 消费者    | 支持     | 支持          | 支持       | 支持 | 支持  | 支持    | 支持|


* **未撰写** [安装](docs/install.md)
* **未撰写** [如何使用生产者](docs/producers_config.md)
* **未撰写** [如何使用消费者](docs/consumers_config.md)
* **未撰写** [消费者如何手动提交](docs/consumers_config.md)


### 生产者
```php
$eventProducers = new \Zwei\ek\EventProducers($clustersConfig, $producersConfig);
// 原生消息发送
$eventProducers->sendMessage($producerName, $message, $key, $milliseconds);

$data = [
    'uid' => 1,
    'accountName' => 'test',
];
$event = \Zwei\ek\Event::getNewInstance()->NewEvent('USER_REGISTER', $data);
// 同步发送
$eventProducers->sendSyncEvent($producerName, $event);
// 异步发送
$eventProducers->sendAsyncEvent($producerName, $event);
```

### 消费者

```php
$consumerName = "normal_user_register";
$eventConsumers = new \Zwei\ek\EventConsumers($clustersConfig, $consumersConfig, $producersConfig);
$eventConsumers->runConsume($consumerName);
```