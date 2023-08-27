<?php

namespace hosannahighertech\sms\interfaces;

use hosannahighertech\sms\events\SMSEvent;
use hosannahighertech\sms\senders\SmsMessage;
use Yii;
use yii\base\Component;

abstract class SmsSenderInterface extends Component
{
    public $enableLogging = true;

    /**
     * Sent SMS Delivery Statuses
     */
    const STATUS_UNKNOWN = 0;
    const STATUS_NEW = 100;
    const STATUS_SENT = 101;
    const STATUS_DELIVERED = 102;
    const STATUS_FAILED = 103;

    /**
     * Events that are emitted
     */
    const EVENT_BEFORE_SEND = 'beforeSend';
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * Send Message. Implement this in your SMS Gateway
     * 
     * @param SmsMessage $message Message Object. It will be passed automatically to this method by the library
     * @return bool Whether the sending was successfully or not.
     */
    abstract protected function sendMessage(SmsMessage $message): bool;

    /**
     * Query message delivery status. Implement this in your SMS Gateway
     * 
     * @param string $mobile the sender mobile number
     * @param string $requestId ID that was sent with this message in receivers part.
     * @return int status. One of the STATUS_** constants
     */
    abstract public function getDeliveryReport(string $mobile, string $requestId): int;

    /**
     * Query message balance. Implement this in your SMS Gateway
     * 
     * @return int balance. The remaining SMS that can be consumed
     */
    abstract public function getBalance(): int;

    /**
     * Send Message. The consumer of this library should call this method to do the sending
     * 
     * @param string $sender sender number or Special ID
     * @param array $receivers receivers, each array member representing single receiver. array format depends on implementation
     * @param string $content text to be sent
     * @param string $preprocess optionl preprocessor. Function signature is `function(SmsMessage $message)` and must return new SmsMessage object
     *
     * @return bool Whether the sending was successfully or not.
     */
    function send(string $sender, array $receivers, string $content, $preprocess = null): bool
    {
        $message = new SmsMessage([
            'sender' => $sender,
            'receivers' => $receivers,
            'content' => $content,
        ]);

        if ($preprocess != null) {
            $message = $preprocess($message);
        }

        $this->beforeSend($message);

        return $this->sendMessage($message);
    }

    protected function beforeSend(SmsMessage $message)
    {
        $event = new SMSEvent;
        $event->message = $message;
        $this->trigger(self::EVENT_BEFORE_SEND, $event);
    }

    protected function afterSend(string $reference)
    {
        $event = new SMSEvent;
        $event->message = $reference;
        $this->trigger(self::EVENT_AFTER_SEND, $event);
    }

    protected function logError(string $message, string $category = 'htcl.sms.error')
    {
        if ($this->enableLogging) {
            Yii::error($message, $category);
        }
    }

    protected function logDebug(string $message, string $category = 'htcl.sms.debug')
    {
        if ($this->enableLogging) {
            Yii::debug($message, $category);
        }
    }

    protected function logInfo(string $message, string $category = 'htcl.sms.info')
    {
        if ($this->enableLogging) {
            Yii::info($message, $category);
        }
    }
}
