<?php
declare(strict_types = 1);

namespace FioApi;

class TransactionListTest extends \PHPUnit\Framework\TestCase
{
    public function testTransactionListValuesAreProperlySet()
    {
        $transactionList = json_decode(file_get_contents(__DIR__ . '/data/example-response.json'));

        $transactionList = TransactionList::create($transactionList->accountStatement);

        $this->assertSame(500.0, $transactionList->getOpeningBalance());
        $this->assertSame(1000.0, $transactionList->getClosingBalance());
        $this->assertEquals(new \DateTimeImmutable('2015-03-30+0200'), $transactionList->getDateStart());
        $this->assertEquals(new \DateTimeImmutable('2015-03-31+0200'), $transactionList->getDateEnd());
        $this->assertSame((float) 1111111111, $transactionList->getIdFrom());
        $this->assertSame((float) 1111111999, $transactionList->getIdTo());
        $this->assertSame(null, $transactionList->getIdLastDownload());
        $this->assertInstanceOf(Account::class, $transactionList->getAccount());
        $this->assertInstanceOf(Transaction::class, $transactionList->getTransactions()[0]);
    }

    public function testEmptyTransactionList()
    {
        $transactionList = json_decode(file_get_contents(__DIR__ . '/data/example-empty-response.json'));

        $transactionList = TransactionList::create($transactionList->accountStatement);

        $this->assertSame(0.0, $transactionList->getOpeningBalance());
        $this->assertSame(0.0, $transactionList->getClosingBalance());
        $this->assertEquals(new \DateTimeImmutable('2017-08-06+0200'), $transactionList->getDateStart());
        $this->assertEquals(new \DateTimeImmutable('2017-08-08+0200'), $transactionList->getDateEnd());
        $this->assertNull($transactionList->getIdFrom());
        $this->assertNull($transactionList->getIdTo());
        $this->assertNull($transactionList->getIdLastDownload());
        $this->assertInstanceOf(Account::class, $transactionList->getAccount());
        $this->assertCount(0, $transactionList->getTransactions());
    }
}
