<?php

namespace hosannahighertech\sms\interfaces;

use hosannahighertech\sms\senders\SmsMessage;
use Yii;
use yii\base\Component;

abstract class SmsSenderInterface extends Component
{
    public $enableLogging = true;

    abstract protected function sendMessage(SmsMessage $message): bool;

    function send(string $sender, array $receivers, string $message): bool
    {
        $message = new SmsMessage([
            'sender' => $sender,
            'receivers' => $receivers,
            'message' => $message,
        ]);
        $message->preProcess();

        return $this->sendMessage($message);
    }

    public function logError(string $message, string $category = 'htcl.sms.error')
    {
        if ($this->enableLogging) {
            Yii::error($message, $category);
        }
    }

    public function logDeb(string $message, string $category = 'htcl.sms.debug')
    {
        if ($this->enableLogging) {
            Yii::error($message, $category);
        }
    }

    public function logInfo(string $message, string $category = 'htcl.sms.info')
    {
        if ($this->enableLogging) {
            Yii::info($message, $category);
        }
    }
}
