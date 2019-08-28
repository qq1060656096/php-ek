# php-ek

介绍
----

ek(Event Kafka)是一个基于事件消费的kafka消费者和生产者, 你可以轻松使用示例和配置开发你的应用

### 2. gapp目录结构
```php
php-ek              根目录
├─config                示例配置目录
├─examples              示例目录
│  ├─EventConsumers.php     事件消费者示例
│  ├─EventProducers.php     事件生产者示例
│  ├─EventConsumeDemoCallback.php   事件消费回调类静态方法(static method)和普通方法(method)示例
│  ├─functionConsumeEvent.php       事件消费回调函数(function)示例
│  └─EventProducersDemoCallbackEvent.php 生产者发送事件消费回调示例事件
├─src                核心源码目录
│  └─Exceptions         异常
├─composer.json      composer包文件
├─LICENSE            授权协议文件
└─README.md          README文件
```


开发文档

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
require_once dirname(__DIR__).'/vendor/autoload.php';

$vendorDir = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir();
$clustersConfigFile = $vendorDir.'/config/zwei.ek.kafka.clusters.php';
$clustersConfig = include $clustersConfigFile;

$producersConfigFile  = $vendorDir.'/config/zwei.ek.kafka.producers.php';
$producersConfig = include $producersConfigFile;
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
require_once dirname(__DIR__).'/vendor/autoload.php';

$vendorDir              = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir();
$clustersConfigFile     = $vendorDir.'/config/zwei.ek.kafka.clusters.php';
$clustersConfig         = include $clustersConfigFile;

$consumersConfigFile    = $vendorDir.'/config/zwei.ek.kafka.consumers.php';
$consumersConfig        = include $consumersConfigFile;

$producersConfigFile    = $vendorDir.'/config/zwei.ek.kafka.producers.php';
$producersConfig        = include $producersConfigFile;


$eventConsumers = new \Zwei\ek\EventConsumers($clustersConfig, $consumersConfig, $producersConfig);
$eventConsumers->runConsume("消费名");
```

### 示例
```sh
# 生产者发送事件消费回调示例事件
/usr/local/php/bin/php examples/EventProducersDemoCallbackEvent.php

# 事件消费者示例
/usr/local/php/bin/php examples/EventConsumers.php normal_event_consume_demo_1
/usr/local/php/bin/php examples/EventConsumers.php 队列名

# 事件生产者示例
/usr/local/php/bin/php examples/EventProducers.php pDefault 1 ping
/usr/local/php/bin/php examples/EventProducers.php "生产者名" "1->同步发送，0->异步发送" "消息内容" "key可选"
```