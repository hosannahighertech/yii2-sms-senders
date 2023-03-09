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

## Events
This library support two events: `beforeSend` which gives you the SMS that will be sent as a payload and `afterSend` which gives you result from the backend. Here is an example on  adding event handlers to the above example

```php
$sms = new \hosannahighertech\sms\senders\BeemSender([
  'key' => 'YOUR_KEY_FROM_BEEM
      'secret' => 'YOUR_SECRET_HERE',
  ]);
  
  $sender->on(SmsSenderInterface::EVENT_BEFORE_SEND, function (SMSEvent $event) {
      $event->message; //this is SMS Object that was sent to the server. Handy to log things if you turned off loggin
  });
  
  $sender->on(SmsSenderInterface::EVENT_AFTER_SEND, function (SMSEvent $event) {
      $event->message; //this is data that comes from backend. For Beem, it is request_id
  });

 $sms->send('SENDER_NAME_OR_NUMBER', ['255....', '255....'], 'Hi, Test Message from Yii2 SMS');
```

## Supported Gateways
- BEEM Africa (see https://beem.africa/sms-api/ )
