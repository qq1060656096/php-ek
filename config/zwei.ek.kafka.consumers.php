<?php
return [
    // 普通队列(注意普通队列以"normal"开始)
    'normal_event_consume_demo_1' => [
        'class' => \Zwei\ek\Consumer::class,// 消费者class
        'groupId'       => 'normal_test',// kafka group_id
        'clusterName'   => 'default',// 集群名: zwei-kafka-cluster.php中配置
        'producerName'  => 'pDefault',// 生产者名
        'timeoutMs'     => 3000,// 消费者超时时间
        'topicNames'    => [// 主题列表
            "test6",
        ],
        'options' => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
        'events' => [
            // 事件名 => callback
            //
            'DemoCallback.Function' => '\Zwei\ek\Examples\functionConsumeEvent',
            'DemoCallback.StaticMethodConsumeEvent' => [\Zwei\ek\Examples\EventConsumeDemoCallback::class, 'staticMethodConsumeEvent'],
            'DemoCallback.MethodConsumeEvent' => [new \Zwei\ek\Examples\EventConsumeDemoCallback(), 'methodConsumeEvent'],
        ],
        'log' => [
            'fileName' => \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/logs/normal_event_consume_demo_1.log',// 日志文件名
            'maxFiles' => 1098,// 保留3年
        ],
    ],
    // 监听队列(注意普通队列以"listen"开始)
    'listen_event_consume_demo_1' => [// 消费者名
        'class'         => \Zwei\ek\Consumer::class,// 消费者class
        'groupId'       => 'listen_test',// kafka group_id
        'clusterName'   => 'default',// 集群名: zwei-kafka-cluster.php中配置
        'producerName'  => 'pDefault',// 生产者名
        'timeoutMs'     => 3000,// 消费者超时时间
        'topicNames'    => [// 主题列表
            "test6",
        ],
        'options' => [// kafka配置选项
            "offset.store.method" => "broker",// offset保存在broker中
        ],
        'events' => [
            // 事件名 => callback
            //
            'DemoCallback.Function' => '\Zwei\ek\Examples\functionConsumeEvent',
            'DemoCallback.StaticMethodConsumeEvent' => [\Zwei\ek\Examples\EventConsumeDemoCallback::class, 'staticMethodConsumeEvent'],
            'DemoCallback.MethodConsumeEvent' => [new \Zwei\ek\Examples\EventConsumeDemoCallback(), 'methodConsumeEvent'],
        ],
        'log' => [
            'fileName' => \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/logs/listen_event_consume_demo_1.log',// 日志文件名
            'maxFiles' => 1098,// 保留3年
        ],
    ],
];