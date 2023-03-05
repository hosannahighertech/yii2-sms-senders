# Yii2 Library for Sending SMSes
Send SMS from different gateways. See readme for currently supported gateways

## How to use Library
To use the library, simply create Instance of the Sender that you want and send the message. Below is an example to get you started

```php
$sms = new \hosannahighertech\sms\senders\BeemSender([
  'key' => 'YOUR_KEY_FROM_BEEM
      'secret' => 'YOUR_SECRET_HERE',
  ]);

 $sms->send('SENDER_NAME_OR_NUMBER', ['255....', '255....'], 'Hi, Test Message from Yii2 SMS');
```

## TODO
- Yii2 Events

## Supported Gateways
- BEEM Africa (see https://beem.africa/sms-api/ )
