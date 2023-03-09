<?php

namespace hosannahighertech\sms\senders;

use hosannahighertech\sms\interfaces\SmsSenderInterface;
use Yii;
use yii\log\Logger;

class BeemSender extends SmsSenderInterface
{
    public $key;
    public $secret;

    private const BASE_URL = 'https://apisms.beem.africa/v1/send';
    private const LOGS_CATEGORY = 'htcl.sms.log';

    public function sendMessage(SmsMessage $message): bool
    {
        $receivers = [];
        foreach ($message->receivers as $receiver) {
            if (isset($receiver['recipient_id'])) { //when defined skip it
                $receivers[] = $receiver;
                continue;
            }

            $receivers[] = [
                'recipient_id' => floor(microtime(true) * 1000), //time in ms
                'dest_addr' => $receiver,
            ];
        }
        $message->receivers = $receivers;

        $postData = array(
            'source_addr' => $message->sender,
            'encoding' => 0,
            'schedule_time' => '',
            'message' => $message->content,
            'recipients' => $message->receivers,
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
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($this->enableLogging) {
            Yii::error($message->toArray());
        }

        if ($response === FALSE) {
            $this->logError(curl_error($ch), self::LOGS_CATEGORY);
            return false;
        } else if ($httpStatus != 200) {
            $this->logError("STATUS CODE {$httpStatus}", self::LOGS_CATEGORY);
            $this->logError($response, self::LOGS_CATEGORY);
            return false;
        } else {
            $this->logDebug($response, self::LOGS_CATEGORY);
            //$this->afterSend($message);
            return true;
        }
    }
}
