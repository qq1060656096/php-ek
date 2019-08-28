<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-24
 * Time: 11:12
 */
namespace Zwei\ek\Examples;

require_once dirname(__DIR__).'/vendor/autoload.php';

$vendorDir = \Zwei\ComposerVendorDirectory\ComposerVendor::getParentDir();
$clustersConfigFile = $vendorDir.'/config/zwei.ek.kafka.clusters.php';
$clustersConfig = include $clustersConfigFile;

$producersConfigFile  = $vendorDir.'/config/zwei.ek.kafka.producers.php';
$producersConfig = include $producersConfigFile;


$producerName   = 'pDefault';
$eventProducers = new \Zwei\ek\EventProducers($clustersConfig, $producersConfig);
$eventList = [
    'DemoCallback.Function',
    'DemoCallback.StaticMethodConsumeEvent',
    'DemoCallback.MethodConsumeEvent',
];
foreach ($eventList as $eventName) {
    $eventProducers->sendSyncEvent($producerName, \Zwei\ek\Event::getNewInstance()->NewEvent($eventName, [$eventName]));
}
