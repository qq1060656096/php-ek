<?php
return [
    // 默认生产者
    'pDefault' => [// 生产者名(必须唯一): 阿里云kafka bbs微服务生产者
        'clusterName'   => 'default',// 集群名(zwei.ek.kafka.cluster.php中配置): 阿里云kafka集群
        'topicName'     => 'test6',// 主题名
        'options'       => [// kafka配置选项
//            "security.protocol"     => "sasl_ssl",
//            "sasl.mechanisms"       => "PLAIN",
//            "api.version.request"   => true,
//            "sasl.username"         => "user",
//            "sasl.password"         => "pass",
//            "ssl.ca.location"       => __DIR__.'/test/ca-cert',// 证书路径
//            "offset.store.method"   => "broker",// offset保存在broker中
        ],
        'log' => [
            'fileName' => \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir().'/logs/pDefault.log',// 日志文件名
            'maxFiles' => 1098,// 保留3年
        ],
    ],
];