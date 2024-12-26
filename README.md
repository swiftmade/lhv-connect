# LHV CONNECT API package for Laravel

This package is a Laravel wrapper for the LHV Connect API.

LHV Connect:
 - [https://www.lhv.ee/en/connect](https://www.lhv.ee/en/connect)

Supported PHP versions: 
  - PHP 7.4+ or PHP 8.0+

Supported Laravel versions:
  - Laravel 7.x, 8.x, 9.x, 10.x, 11.x

## Quickstart

    $ composer require swiftmade/lhv-connect

NB! Service provider Swiftmade\LhvConnect\LaravelLhvConnectServiceProvider::class is automatically registered.

In terminal run

    $ php artisan vendor:publish

Open file config/lhv-connect.php and fill out the config. You can fill in info about several bank accounts and certifications.

Now you can create new LhvConnect object. The Config::get parameter lhv-connect.test means that the file lhv-connect.php
and the array with the key 'test' is passed on.

    $lhv = new LhvConnect(Config::get('lhv-connect.test'));

Test the connection. If there's no connection, Exception with 503 should be thrown.

    $lhv->makeHeartbeatGetRequest();

Retrieve a message from LHV inbox

    $message = $lhv->makeRetrieveMessageFromInboxRequest();

Delete the message from LHV inbox

    $lhv->makeDeleteMessageInInboxRequest($message);

Retrieve all messages. This gets you all the messages but it also deletes all the messages from the inbox.

    $messages = $lhv->getAllMessages();

---

### Acknowledgements

Based on original package by Mihkel Allorg released under MIT license.
https://github.com/mihkelallorg/lhv-connect/blob/master/LICENSE