<?php

namespace FioApi;

class EuroPaymentBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SimpleXMLElement
     */
    protected $request;

    public function testImplementsPaymentBuilder()
    {
        $this->assertInstanceOf('FioApi\PaymentBuilder', new EuroPaymentBuilder());
    }

    public function testEuroTransaction()
    {
        $tx = $this->request->Orders[0]->T2Transaction;
        $this->assertEquals('214498596', (string) $tx->accountFrom);
        $this->assertEquals('EUR', (string) $tx->currency);
        $this->assertEquals('100.00', (string) $tx->amount);
        $this->assertEquals('AT611904300234573201', (string) $tx->accountTo);
        $this->assertEquals('ABAGATWWXXX', (string) $tx->bic);
        $this->assertEquals('0558', (string) $tx->ks);
        $this->assertEquals('1234567890', (string) $tx->vs);
        $this->assertEquals('0987654321', (string) $tx->ss);
        $this->assertEquals('2016-04-18', (string) $tx->date);
        $this->assertEquals('Comment', (string) $tx->comment);
        $this->assertEquals('431008', (string) $tx->paymentType);
    }

    protected function setUp()
    {
        $builder = new EuroPaymentBuilder();
        $account = $this->createAccount();
        $tx = $this->createTransaction();
        $this->request = $builder->build($account, [$tx]);
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
        $mock->method('getCurrency')->willReturn('EUR');
        $mock->method('getAmount')->willReturn(100.0);
        $mock->method('getAccountNumber')->willReturn('AT611904300234573201');
        $mock->method('getBankCode')->willReturn('ABAGATWWXXX');
        $mock->method('getConstantSymbol')->willReturn('0558');
        $mock->method('getVariableSymbol')->willReturn('1234567890');
        $mock->method('getSpecificSymbol')->willReturn('0987654321');
        $mock->method('getDate')->willReturn(new \DateTime('2016-04-18+0200'));
        $mock->method('getUserMessage')->willReturn('Groceries Foo, Inc.');
        $mock->method('getComment')->willReturn('Comment');
        $mock->method('getSpecification')->willReturn('431008');
        $mock->method('getBenefName')->willReturn('Hans Gruber');
        $mock->method('getBenefStreet')->willReturn('Gugitzgasse 2');
        $mock->method('getBenefCity')->willReturn('Wien');
        $mock->method('getBenefCountry')->willReturn('AT');

        return $mock;
    }
}
