# Fio API PHP implemention [![Build Status](https://travis-ci.org/mhujer/fio-api-php.svg?branch=master)](https://travis-ci.org/mhujer/fio-api-php) [![Build Status Windows](https://ci.appveyor.com/api/projects/status/github/mhujer/fio-api-php?branch=master&svg=true)](https://ci.appveyor.com/project/mhujer/fio-api-php/branch/master)

[![Latest Stable Version](https://poser.pugx.org/mhujer/fio-api-php/version.png)](https://packagist.org/packages/mhujer/fio-api-php) [![Total Downloads](https://poser.pugx.org/mhujer/fio-api-php/downloads.png)](https://packagist.org/packages/mhujer/fio-api-php) [![License](https://poser.pugx.org/mhujer/fio-api-php/license.svg)](https://packagist.org/packages/mhujer/fio-api-php) [![Coverage Status](https://coveralls.io/repos/mhujer/fio-api-php/badge.svg?branch=master)](https://coveralls.io/r/mhujer/fio-api-php?branch=master)

Fio bank REST API implementation in PHP. It allows you to download and iterate through account balance changes.

[There is a Symfony Bundle](https://github.com/mhujer/fio-api-bundle) for using this library in a Symfony app.

Usage
----
1. Install the latest version with `composer require mhujer/fio-api-php`
2. Create a *token* in the ebanking (Nastavení / API)
3. Use it according to the example bellow and check the docblocks

### Downloading 
```php
<?php
require_once 'vendor/autoload.php';

$downloader = new FioApi\Downloader('TOKEN@todo');
$transactionList = $downloader->downloadSince(new \DateTime('-1 week'));

foreach ($transactionList->getTransactions() as $transaction) {
    var_dump($transaction); //object with getters
}

```

### Uploading

#### Domestic payment (in Czechia)
```php
<?php
require_once 'vendor/autoload.php';

$token = get_your_fio_token();
$uploader = new FioApi\Uploader($token);
// currency, iban, bic is not needed
$account = new FioApi\Account('XXXXXXXXXX', 'ZZZZ', NULL, NULL, NULL);
$tx = Transaction::create((object) [
    'accountNumber' => 'YYYYYYYYYY',
    'bankCode' => 'WWWW',
    'date' => new \DateTime('2016-07-20'),
    'amount' => 6.66,
    'currency' => 'CZK',
    'userMessage' => 'money wasting',
    'comment' => 'fioapi.test'
]);

$builder = new FioApi\DomesticPaymentBuilder();
$request = $builder->build($account, [$tx]);
$response = $uploader->sendRequest($request);

echo $response->getStatus();
```

#### European payments
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$token = get_your_fio_token();
$uploader = new FioApi\Uploader($token);
$account = new FioApi\Account('XXXXXXXXXX', 'YYYY', null, null, null);
$tx = FioApi\Transaction::create((object) [
    'accountNumber' => 'XXXXXXXXXXXXXXXX',
    'bankCode'      => 'WWWWWWWWWW',
    'date'          => new DateTime('2016-05-30'),
    'amount'        => 66.5,
    'currency'      => 'EUR',
    'userMessage'   => 'Donation for poor ones',
    'comment'       => 'fioapi.test',
    'benefName'     => 'Something Finland Oy',
    'benefCountry'  => 'FI',
]);

$builder = new FioApi\EuroPaymentBuilder();
$request = $builder->build($account, [$tx]);
$response = $uploader->sendRequest($request);

echo $response->getStatus();
echo "\n";
```

#### International payments (outside EU)
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$token = get_your_fio_token();
$uploader = new FioApi\Uploader($token);
$account = new FioApi\Account('XXXXXXXXXX', 'YYYY', null, null, null);
$tx = FioApi\Transaction::create((object) [
    'accountNumber' => 'XXXXXXXXXXXXXXXX',
    'bankCode'      => 'WWWWWWWWWW',
    'date'          => new DateTime('2016-05-30'),
    'amount'        => 2,
    'currency'      => 'USD',
    'userMessage'   => 'Donation for homelesses at 6th Street',
    'comment'       => 'fioapi.test',
    'benefName'     => 'John Doe',
    'benefStreet'   => '6th Street',
    'benefCity'     => 'San Francisco, CA',
    'benefCountry'  => 'US',
]);

$builder = new FioApi\InternationalPaymentBuilder();
$request = $builder->build($account, [$tx]);
$response = $uploader->sendRequest($request);

echo $response->getStatus();
echo "\n";
```


Requirements
------------
Fio API PHP works with PHP 5.5, PHP 5.6 or PHP 7.

Submitting bugs and feature requests
------------------------------------
Bugs and feature request are tracked on [GitHub](https://github.com/mhujer/fio-api-php/issues)

Author
------
Martin Hujer - <mhujer@gmail.com> - <https://www.martinhujer.cz>

Changelog
----------
## 2.2.0 (2016-03-13)
- [#2](https://github.com/mhujer/fio-api-php/pull/2): added [Kdyby/CurlCaBundle](https://github.com/Kdyby/CurlCaBundle)
 	as an optional dependency (@mhujer)

## 2.1.0 (2016-03-12)
- [#1](https://github.com/mhujer/fio-api-php/pull/1): updated default GeoTrust certificate (@soukiii)
- [#1](https://github.com/mhujer/fio-api-php/pull/1): added `specification` field in transaction (@soukiii)

## 2.0.0 (2015-06-14)
- upgraded to Guzzle 6
- support for PHP 5.4 dropped (as Guzzle 6 requires PHP 5.5+)

## 1.0.3 (2015-06-14)
- updated root certificate (Root 3) as the Fio changed it on 2014-05-26

## 1.0.0 (2015-04-05)
- initial release
