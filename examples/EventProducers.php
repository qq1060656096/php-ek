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

$producersConfigFile  = $vendorDir.'/config/zwei.ek.kafka.producers.php';
$producersConfig = include $producersConfigFile;

if (!isset($argv[3])) {
    die("producer.params.error");
}
$producerName = $argv[1];
$isSync = $argv[2] ? true : false;
$milliseconds = $isSync ? -1 : 0;
$message = $argv[3];
$key = isset($argv[4]) ? $argv[4] : null;
$eventProducers = new \Zwei\ek\EventProducers($clustersConfig, $producersConfig);
$eventProducers->sendMessage($producerName, $message, $key, $milliseconds);
