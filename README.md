## EventSource HQ PHP Example

This is a small example chat application using EventSource HQ to push
messages to the clients.

To install on Heroku:

    git clone git://github.com/eshq/chat.git
    cd chat
    heroku create
    heroku addons:add eshq
    git push heroku master

The app comes with an [ESHQ class](https://github.com/eshq/php-eshq-chat/blob/master/include/eshq.php) for general use when using
EventSource HQ from PHP.

Usage:

```php
$eshq = new ESHQ();

// Get a new socket_id for connecting to a channel
$token = $eshq->open(array("channel" => "my-channel"));

// Post a message to a channel
$eshq->send(array("channel" => "my-channel", "data" => "My message"));
```
