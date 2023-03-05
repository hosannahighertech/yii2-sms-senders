<?php

namespace hosannahighertech\sms\interfaces;

use hosannahighertech\sms\senders\SmsMessage;
use Yii;
use yii\base\Component;

abstract class SmsSenderInterface extends Component
{
    public $enableLogging = true;

    abstract protected function sendMessage(SmsMessage $message): bool;

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

        return $this->sendMessage($message);
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
