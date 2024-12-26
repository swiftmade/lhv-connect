# LHV CONNECT API package for Laravel


Supported Laravel versions: 5.5, 5.6, 5.7, 5.8, 6.0, 7.0, 8.0

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