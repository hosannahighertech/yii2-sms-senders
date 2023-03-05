<?php

namespace hosannahighertech\sms\senders;

use yii\base\BaseObject;

class SmsMessage extends BaseObject
{
    public string $sender;
    public array $receivers;
    public string $message;

    public function preProcess()
    {
    }
}
