<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-08-15
 * Time: 15:32
 */

namespace Zwei\ek;


use RdKafka\Message;
use Zwei\ek\Exceptions\EkBaseException;

class Consumer extends ConsumerAbstract
{

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
        $this->getLogger()->info("consumer.message", [$message]);
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $payload = $message->payload;
                if ($payload === 'custom.message.heart') {
                    $this->getLogger()->info("consumer.message.heart", [$payload]);
                    return;
                }
                $eventArr = json_decode($payload, true);
                // 不是事件格式不处理
                if (!is_array($eventArr) || empty($eventArr)) {
                    return;
                    break;
                }

                $event = Event::parseArray($eventArr);
                try {
                    $callback = $this->getEventConsumeConfigs()->get($event->getName())->getCallback();
                    $evenConsumeResult = call_user_func($callback, [$event]);
                    if ($evenConsumeResult instanceof EventConsumeResult && !$evenConsumeResult->getStatus()) {
                        $this->getLogger()->log("consumer.event.consumeFail", get_object_vars($evenConsumeResult));
                        return;
                    }
                    // 广播事件
                    $this->broadcast($message->topic_name, $event);
                    return;
                } catch (\Exception $e) {// 异常处理
                    $this->getLogger()->info("consumer.event.exception", [
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
        $event['time'] = time();
        // 不是广播事件, 才广播
        $this->producer->sendTopicMessage($topicName, (string)$event, $event->getKey());
        return true;
    }
}