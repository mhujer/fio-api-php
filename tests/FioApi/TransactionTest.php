<?php

namespace FioApi;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountValuesAreProperlySet()
    {
        $transaction = json_decode(file_get_contents(__DIR__.'/data/example-transaction.json'));
        $transaction = Transaction::createFromJson($transaction);

        $this->assertSame(1111111111, $transaction->getId());
        $this->assertEquals(new \DateTime('2015-03-30+0200'), $transaction->getDate());
        $this->assertSame(127.0, $transaction->getAmount());
        $this->assertSame('CZK', $transaction->getCurrency());
        $this->assertSame('214498596', $transaction->getAccountNumber());
        $this->assertSame('2100', $transaction->getBankCode());
        $this->assertSame('0', $transaction->getVariableSymbol());
        $this->assertSame(null, $transaction->getConstantSymbol());
        $this->assertSame(null, $transaction->getSpecificSymbol());
        $this->assertSame('Banka, a.s.', $transaction->getBankName());
        $this->assertSame('HUJER MARTIN', $transaction->getUserIdentity());
        $this->assertSame('Platba eshop', $transaction->getUserMessage());
        $this->assertSame('Bezhotovostní příjem', $transaction->getTransactionType());
        $this->assertSame(null, $transaction->getPerformedBy());
        $this->assertSame('Comment? Comment!', $transaction->getComment());
        $this->assertSame(1111122222, $transaction->getPaymentOrderId());
        $this->assertSame('1500.00 EUR', $transaction->getSpecification());
    }

    public function testRemittanceInfoWithShortMessage()
    {
        $remittanceInfo = 'Rent for apartment door 12';
        $tx = Transaction::create((object) [
            'date'        => new \DateTime('2016-05-01'),
            'amount'      => '66.00',
            'currency'    => 'USD',
            'userMessage' => $remittanceInfo,
        ]);

        $this->assertEquals($remittanceInfo, $tx->getRemittanceInfo1());
        $this->assertEquals('', $tx->getRemittanceInfo2());
        $this->assertEquals('', $tx->getRemittanceInfo3());
        $this->assertEquals('', $tx->getRemittanceInfo4());
    }

    public function testRemittanceInfoWith140CharacterMessage()
    {
        $remittanceInfo = [
            'Lorem ipsum dolor sit amet, consect',
            'etur adipiscing elit. Nulla arcu ur',
            'na, condimentum in justo eu, sceler',
            'isque vulputate nisl. Sed volutpat.',
        ];

        $tx = Transaction::create((object) [
            'date'        => new \DateTime('2016-07-20'),
            'amount'      => 120.00,
            'currency'    => 'CZK',
            'userMessage' => implode($remittanceInfo),
        ]);

        $this->assertEquals($remittanceInfo[0], $tx->getRemittanceInfo1());
        $this->assertEquals($remittanceInfo[1], $tx->getRemittanceInfo2());
        $this->assertEquals($remittanceInfo[2], $tx->getRemittanceInfo3());
        $this->assertEquals($remittanceInfo[3], $tx->getRemittanceInfo4());
    }
}
