<?php

namespace hosannahighertech\sms\senders;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;
use yii\base\BaseObject;

class SmsMessage extends BaseObject implements Arrayable
{
    use ArrayableTrait;

    public string $sender;
    public array $receivers;
    public string $content;
}
