# LHV CONNECT API package for Laravel

This package is a Laravel wrapper for the LHV Connect API.

LHV Connect:
 - [https://www.lhv.ee/en/connect](https://www.lhv.ee/en/connect)

Supported PHP versions: 
  - PHP 8.1+

Supported Laravel versions:
  - Laravel 8.x, 9.x, 10.x, 11.x

## Quickstart

    $ composer require swiftmade/lhv-connect

NB! Service provider Swiftmade\LhvConnect\LhvConnectServiceProvider::class is automatically registered.

In terminal run

    $ php artisan vendor:publish --provider="Swiftmade\LhvConnect\LhvConnectServiceProvider"

Open file config/lhv-connect.php and fill out the config. You can fill in info about several bank accounts and certifications.


## Usage

```php

use Swiftmade\LhvConnect\LhvConnect;

$lhv = LhvConnect::make('sandbox'); // sandbox is a key under lhv-connect.accounts

$lhv->sendHeartbeat(); // test connection

$lhv->getAccountBalance(); // get account balance

$lhv->getAccountStatement(new DateTime('2024-01-01'), new DateTime('2024-01-31')); // get account statement

```

### Error Handling

The package will throw exceptions in the following cases:
- Invalid configuration
- API errors (LhvApiError)
- Connection issues
- Request timeout

### Notes

- The package uses a locking mechanism to handle LHV Connect's asynchronous responses
- Default timeout for requests is 2 seconds
- Requests use exponential backoff for retries

### Acknowledgements

Based on original package by Mihkel Allorg released under MIT license.
https://github.com/mihkelallorg/lhv-connect/blob/master/LICENSE