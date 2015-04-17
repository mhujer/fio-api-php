<?php

namespace FioApi;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountValuesAreProperlySet()
    {
        $transaction = json_decode(file_get_contents(__DIR__ . '/data/example-transaction.json'));

        $transaction = Transaction::create($transaction);

        $this->assertSame(127.0, $transaction->getAmount());
        $this->assertSame('CZK', $transaction->getCurrency());
        $this->assertSame('214498596', $transaction->getSenderAccountNumber());
        $this->assertSame('2100', $transaction->getSenderBankCode());
        $this->assertSame('0', $transaction->getVariableSymbol());
        $this->assertSame(null, $transaction->getConstantSymbol());
        $this->assertSame(null, $transaction->getSpecificSymbol());
        $this->assertSame('Banka, a.s.', $transaction->getSenderBankName());
        $this->assertSame('HUJER MARTIN', $transaction->getUserIdentity());
        $this->assertSame('Platba eshop', $transaction->getUserMessage());
        $this->assertSame(null, $transaction->getPerformedBy());
        $this->assertSame('Comment? Comment!', $transaction->getComment());
        $this->assertSame(1111122222, $transaction->getPaymentOrderId());
    }
}
