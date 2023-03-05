<?php

namespace hosannahighertech\sms\senders;

use hosannahighertech\sms\interfaces\SmsSenderInterface;
use yii\log\Logger;

class BeemSender extends SmsSenderInterface
{
    public $key;
    public $secret;

    private const BASE_URL = 'https://apisms.beem.africa/v1/send';
    private const LOGS_CATEGORY = 'htcl.sms.log';

    public function sendMessage(SmsMessage $message): bool
    {
        $postData = array(
            'source_addr' => $message->sender,
            'encoding' => 0,
            'schedule_time' => '',
            'message' => $message->content,
            'recipients' => $message->numbers,
        );


        $ch = curl_init(self::BASE_URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("{$this->key}:{$this->secret}"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);
        $this->log(Logger::LEVEL_INFO, $response, self::LOGS_CATEGORY);

        if ($response === FALSE) {
            $this->logError(curl_error($ch), self::LOGS_CATEGORY);
            return false;
        } else {
            //$this->afterSend($message);
            return true;
        }
    }
}
