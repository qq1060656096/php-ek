<?php
return [
    'normal_payment_call_1' => [// 消费者名
        'class' => \Zwei\ek\Consumer::class,// 消费者class
        'groupId'       => 'test',// kafka group_id
        'clusterName'   => 'default',// 集群名: zwei-kafka-cluster.php中配置
        'producerName'  => 'v0pBbs',// 生产者名
        'topicNames'    => [// 主题列表
            "test6",
        ],
        'timeoutMs' => 5000,// 消费者超时时间
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
        'events'    => [
            // 事件名 => callback
            // 初始化事件 CRM_ZNTK_INIT
            'USER_REGISTER' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',
        ],
        'log' => [
            'fileName' => \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/logs/normal_payment_call.log',// 日志文件名
            'maxFiles' => 1098,// 保留3年
        ],
    ],
    'normal_payment_call_2' => [// 消费者名
        'class' => \Zwei\ek\Consumer::class,// 消费者class
        'groupId'       => 'test',// kafka group_id
        'clusterName'   => 'default',// 集群名: zwei-kafka-cluster.php中配置
        'producerName'  => 'v0pBbs',// 生产者名
        'topicNames'    => [// 主题列表
            "test6",
        ],
        'timeoutMs' => 5000,// 消费者超时时间
        'options'   => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
        'events'    => [
            // 事件名 => callback
            // 初始化事件 CRM_ZNTK_INIT
            'USER_REGISTER' => '\Zwei\Kafka\AppEventConsumer::testEventCallback',
        ],
        'log' => [
            'fileName' => \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/logs/normal_payment_call_2.log',// 日志文件名
            'maxFiles' => 1098,// 保留3年
        ],
    ],
];