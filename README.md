# Fio API PHP implemention [![Build Status](https://travis-ci.org/mhujer/fio-api-php.svg?branch=master)](https://travis-ci.org/mhujer/fio-api-php)

[![Latest Stable Version](https://poser.pugx.org/mhujer/fio-api-php/version.svg)](https://packagist.org/packages/mhujer/fio-api-php) [![Total Downloads](https://poser.pugx.org/mhujer/fio-api-php/downloads.svg)](https://packagist.org/packages/mhujer/fio-api-php) [![License](https://poser.pugx.org/mhujer/fio-api-php/license.svg)](https://packagist.org/packages/mhujer/fio-api-php)

Fio bank REST API implementation in PHP. It allows you to download and iterate through account balance changes.

Usage
----
1. Install the latest version with `composer require mhujer/fio-api-php`
2. Create a *token* in the ebanking (Nastavení / API)
3. Use it according to the example bellow and check the docblocks

```php
<?php
require_once 'vendor/autoload.php';

$downloader = new FioApi\Downloader('TOKEN@todo');
$transactionList = $downloader->downloadSince(new \DateTime('-1 week'));

foreach ($transactionList->getTransactions() as $transaction) {
    var_dump($transaction); //object with getters
}

```

Requirements
------------
Fio API PHP works with PHP 5.4 or above (or HHVM)

Submitting bugs and feature requests
------------------------------------
Bugs and feature request are tracked on [GitHub](https://github.com/mhujer/fio-api-php/issues)

Author
------
Martin Hujer - <mhujer@gmail.com> - <http://www.martinhujer.cz>
