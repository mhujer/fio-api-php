<?php

namespace FioApi;

class DomesticPaymentBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SimpleXMLElement
     */
    protected $request;

    public function testDomesticTransaction()
    {
        $tx = $this->request->Orders[0]->DomesticTransaction;
        $this->assertEquals('214498596', (string) $tx->accountFrom);
        $this->assertEquals('CZK', (string) $tx->currency);
        $this->assertEquals('100.00', (string) $tx->amount);
        $this->assertEquals('2212-2000000699', (string) $tx->accountTo);
        $this->assertEquals('0300', (string) $tx->bankCode);
        $this->assertEquals('0558', (string) $tx->ks);
        $this->assertEquals('1234567890', (string) $tx->vs);
        $this->assertEquals('0987654321', (string) $tx->ss);
        $this->assertEquals('2016-04-18', (string) $tx->date);
        $this->assertEquals('Groceries Foo, Inc.', (string) $tx->messageForRecipient);
        $this->assertEquals('Comment', (string) $tx->comment);
        $this->assertEquals('431001', (string) $tx->paymentType);
    }

    protected function setUp()
    {
        $builder = new DomesticPaymentBuilder();
        $account = $this->createAccount();
        $tx = $this->createTransaction();

        $this->request = simplexml_load_string($builder->build($account, [$tx]));
    }

    protected function tearDown()
    {
        $this->request = null;
    }

    protected function createAccount()
    {
        $mock = $this->getMockBuilder('FioApi\Account')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getAccountNumber')->willReturn('214498596');

        return $mock;
    }

    protected function createTransaction()
    {
        $mock = $this->getMockBuilder('FioApi\Transaction')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getCurrency')->willReturn('CZK');
        $mock->method('getAmount')->willReturn(100.0);
        $mock->method('getAccountNumber')->willReturn('2212-2000000699');
        $mock->method('getBankCode')->willReturn('0300');
        $mock->method('getConstantSymbol')->willReturn('0558');
        $mock->method('getVariableSymbol')->willReturn('1234567890');
        $mock->method('getSpecificSymbol')->willReturn('0987654321');
        $mock->method('getDate')->willReturn(new \DateTime('2016-04-18+0200'));
        $mock->method('getUserMessage')->willReturn('Groceries Foo, Inc.');
        $mock->method('getComment')->willReturn('Comment');
        $mock->method('getSpecification')->willReturn('431001');

        return $mock;
    }
}
