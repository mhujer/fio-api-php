<?php
declare(strict_types = 1);

namespace FioApi;

use PHPUnit\Framework\Assert;

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    public function testAccountValuesAreProperlySet(): void
    {
        $transaction = json_decode((string) file_get_contents(__DIR__ . '/data/example-transaction.json'));

        $transaction = Transaction::create($transaction);

        Assert::assertSame('1111111111', $transaction->getId());
        Assert::assertEquals(new \DateTimeImmutable('2015-03-30+0200'), $transaction->getDate());
        Assert::assertSame(127.0, $transaction->getAmount());
        Assert::assertSame('CZK', $transaction->getCurrency());
        Assert::assertSame('214498596', $transaction->getSenderAccountNumber());
        Assert::assertSame('2100', $transaction->getSenderBankCode());
        Assert::assertSame('HUJER MARTIN', $transaction->getSenderName());
        Assert::assertSame('0', $transaction->getVariableSymbol());
        Assert::assertNull($transaction->getConstantSymbol());
        Assert::assertNull($transaction->getSpecificSymbol());
        Assert::assertSame('Banka, a.s.', $transaction->getSenderBankName());
        Assert::assertSame('HUJER MARTIN', $transaction->getUserIdentity());
        Assert::assertSame('Platba eshop', $transaction->getUserMessage());
        Assert::assertSame('Bezhotovostní příjem', $transaction->getTransactionType());
        Assert::assertNull($transaction->getPerformedBy());
        Assert::assertSame('Comment? Comment!', $transaction->getComment());
        Assert::assertSame((float) 1111122222, $transaction->getPaymentOrderId());
        Assert::assertSame('1500.00 EUR', $transaction->getSpecification());
    }
}
