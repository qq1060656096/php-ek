<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-24
 * Time: 11:12
 */

require_once dirname(__DIR__).'/vendor/autoload.php';

$vendorDir = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir();
$clustersConfigFile = $vendorDir.'/config/zwei.ek.kafka.clusters.php';
$clustersConfig = include $clustersConfigFile;

$consumersConfigFile  = $vendorDir.'/config/zwei.ek.kafka.consumers.php';
$consumersConfig = include $consumersConfigFile;

$producersConfigFile  = $vendorDir.'/config/zwei.ek.kafka.producers.php';
$producersConfig = include $producersConfigFile;

if (!isset($argv[1])) {
    die("consumer.consumerName.required");
}
$consumerName = $argv[1];
$eventConsumers = new \Zwei\ek\EventConsumers($clustersConfig, $consumersConfig, $producersConfig);
$eventConsumers->runConsume($consumerName);
