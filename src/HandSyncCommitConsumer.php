<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:32
 */

namespace Zwei\ek;


use RdKafka\Message;
use Zwei\ek\Exceptions\ConsumerEventConfigNotFoundException;
use Zwei\ek\Exceptions\EkBaseException;

/**
 * 手动同步提交
 * Class HandSyncCommitConsumer
 * @package Zwei\ek
 */
class HandSyncCommitConsumer extends ConsumerAbstract
{

    /**
     * 设置必要的kafka配置
     */
    protected function setRequiredRdKafkaConfig()
    {
        $this->rdKafkaConf->set("enable.auto.commit", "true");
        parent::setRequiredRdKafkaConfig();
    }



    public function runConsume()
    {
        // TODO: Implement runConsume() method.
        $kafkaConsumer   = $this->getRdKafkaConsumer();

        while (true) {
            $kafkaMessage = $kafkaConsumer->consume($this->getConsumerConfig()->getTimeoutMs());
            $this->consume($kafkaMessage);
        }
    }

    /**
     * 消费事件
     * @param Message $message
     * @return mixed|void
     * @throws \Exception
     */
    public function consume(Message $message)
    {
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $payload = $message->payload;
                $this->getLogger()->info("consumer.message.payload", [$payload]);
                if ($payload === 'custom.message.heart') {
                    $this->getLogger()->info("consumer.message.heart", [$payload]);
                    $this->getRdKafkaConsumer()->commit($message);
                    return;
                }
                $eventArr = json_decode($payload, true);
                // 不是事件格式不处理
                if (!is_array($eventArr) || empty($eventArr)) {
                    $this->getRdKafkaConsumer()->commit($message);
                    return;
                    break;
                }

                $event = Event::parseArray($eventArr);
                try {
                    $callback = $this->getEventConsumeConfigs()->get($event->getName())->getCallback();
                    $evenConsumeResult = call_user_func_array($callback, [$message, $event]);
                    if ($evenConsumeResult instanceof EventConsumeResult && $evenConsumeResult->getStatus() !== true) {
                        $this->getLogger()->info("consumer.event.consume.fail",
                            [
                                'event' => (string)$event,
                                get_object_vars($evenConsumeResult)
                            ]
                        );
                        return;
                    }
                    // 广播事件
                    $this->broadcast($message->topic_name, $event);
                    $this->getRdKafkaConsumer()->commit($message);
                    return;
                } catch (ConsumerEventConfigNotFoundException $e) {
                    $this->getRdKafkaConsumer()->commit($message);
                    $this->getLogger()->info("consumer.event.consume.config.notFund", [
                        'event' => (string)$event,
                        'exceptionMsg' => $e->getMessage(),
                    ]);
                } catch (\Exception $e) {// 异常处理
                    $this->getLogger()->info("consumer.event.consume.exception", [
                        'event' => (string)$event,
                        'consumerConfig' => $this->getConsumerConfig()->getAll(),
                        'exception' => EkBaseException::convertArray($e),
                    ]);
                }
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:// 没有消息
                $this->getLogger()->info("consumer.message.noMoreMessage");
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:// 超时
                $this->getLogger()->info("consumer.message.timedOut");
                break;
            default:
                $this->getLogger()->info("consumer.message.exception", [
                    '$message' => $message,
                ]);
                throw new \Exception($message->errstr(), $message->err);
                break;
        }
    }


    /**
     * 广播事件
     *
     * @param string $topicName
     * @param Event $event
     * @return bool
     */
    public function broadcast($topicName, Event $event)
    {
        // 普通队列并且不是广播事件才能广播
        if ($this->type != self::TYPE_NORMAL) {
            return false;
        }
        $event->getName();
        $broadcastEventSuffix = '_SUCCESS';
        $broadcastEventSuffixLen = strlen($broadcastEventSuffix);
        $isBroadcastEvent = substr_compare($event->getName(), $broadcastEventSuffix, -$broadcastEventSuffixLen) === 0;
        // 如果找到后缀，代表是广播事件
        if ($isBroadcastEvent) {
            return false;
        }
        $successEventName = $event->getName().$broadcastEventSuffix;
        $event->setName($event->getName(), $successEventName);
        // 不是广播事件, 才广播
        $this->producer->sendTopicMessage($topicName, (string)$event, $event->getKey());
        return true;
    }
}
