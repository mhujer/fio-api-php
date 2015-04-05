<?php

namespace FioApi;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountValuesAreProperlySet()
    {
        $transaction = json_decode(file_get_contents(__DIR__ . '/data/example-transaction.json'));

        $transaction = Transaction::create($transaction);

        $this->assertEquals(127, $transaction->getAmount());
        $this->assertEquals('CZK', $transaction->getCurrency());
        $this->assertEquals('214498596', $transaction->getSenderAccountNumber());
        $this->assertEquals('2100', $transaction->getSenderBankCode());
    }
}
