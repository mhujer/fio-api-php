# Fio API PHP implemention [![Build Status](https://travis-ci.org/mhujer/fio-api-php.svg?branch=master)](https://travis-ci.org/mhujer/fio-api-php) [![Build Status Windows](https://ci.appveyor.com/api/projects/status/github/mhujer/fio-api-php?branch=master&svg=true)](https://ci.appveyor.com/project/mhujer/fio-api-php/branch/master)

[![Latest Stable Version](https://poser.pugx.org/mhujer/fio-api-php/version.png)](https://packagist.org/packages/mhujer/fio-api-php) [![Total Downloads](https://poser.pugx.org/mhujer/fio-api-php/downloads.png)](https://packagist.org/packages/mhujer/fio-api-php) [![License](https://poser.pugx.org/mhujer/fio-api-php/license.svg)](https://packagist.org/packages/mhujer/fio-api-php) [![Coverage Status](https://coveralls.io/repos/mhujer/fio-api-php/badge.svg?branch=master)](https://coveralls.io/r/mhujer/fio-api-php?branch=master)

Fio bank REST API implementation in PHP. It allows you to download and iterate through account balance changes.

[There is a Symfony Bundle](https://github.com/mhujer/fio-api-bundle) for using this library in a Symfony app.

Usage
----
1. Install the latest version with `composer require mhujer/fio-api-php`
2. Create a *token* in the ebanking (Nastavení / API)
3. Use it according to the example bellow and check the docblocks

```php
<?php
require_once 'vendor/autoload.php';

$downloader = new FioApi\Downloader('TOKEN@todo');
$transactionList = $downloader->downloadSince(new \DateTimeImmutable('-1 week'));

foreach ($transactionList->getTransactions() as $transaction) {
    var_dump($transaction); //object with getters
}

```

Requirements
------------
Fio API PHP works with PHP 7 or higher.

Submitting bugs and feature requests
------------------------------------
Bugs and feature request are tracked on [GitHub](https://github.com/mhujer/fio-api-php/issues)

Author
------
Martin Hujer - <mhujer@gmail.com> - <https://www.martinhujer.cz>

Changelog
----------

## 4.0.1 (2017-08-09)
- [#12](https://github.com/mhujer/fio-api-php/pull/12) handle empty transaction list  (thx @soukicz!)

## 4.0.0 (2017-08-04)
- [#9](https://github.com/mhujer/fio-api-php/pull/9) minimal supported version is PHP 7.1
- [#9](https://github.com/mhujer/fio-api-php/pull/9)`DateTime` replaced with `DateTimeImmutable` (or `DateTimeInterface`)
- [#9](https://github.com/mhujer/fio-api-php/pull/9) strict types and primitive typehints are used everywhere

## 3.0.0 (2016-11-24)
- dropped support for PHP <7

## 2.3.0 (2016-11-24)
- [#7](https://github.com/mhujer/fio-api-php/pull/7): added official composer CA bundle support (@soukicz)

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
