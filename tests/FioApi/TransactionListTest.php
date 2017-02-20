<?php

namespace FioApi;

class TransactionListTest extends \PHPUnit_Framework_TestCase
{
    public function testTransactionListValuesAreProperlySet()
    {
        $transactionList = json_decode(file_get_contents(__DIR__.'/data/example-response.json'));

        $transactionList = TransactionList::create($transactionList->accountStatement);

        $this->assertSame(500, $transactionList->getOpeningBalance());
        $this->assertSame(1000, $transactionList->getClosingBalance());
        $this->assertEquals(new \DateTime('2015-03-30+0200'), $transactionList->getDateStart());
        $this->assertEquals(new \DateTime('2015-03-31+0200'), $transactionList->getDateEnd());
        $this->assertSame(1111111111, $transactionList->getIdFrom());
        $this->assertSame(1111111999, $transactionList->getIdTo());
        $this->assertSame(null, $transactionList->getIdLastDownload());
        $this->assertInstanceOf('FioApi\Account', $transactionList->getAccount());
        $this->assertInstanceOf('FioApi\Transaction', $transactionList->getTransactions()[0]);
    }
}
