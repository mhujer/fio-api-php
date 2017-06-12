<?php

namespace FioApi;

class InternationalPaymentBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SimpleXMLElement
     */
    protected $request;

    public function testImplementsPaymentBuilder()
    {
        $this->assertInstanceOf('FioApi\PaymentBuilder', new InternationalPaymentBuilder());
    }

    public function testXmlTransaction()
    {
        $tx = $this->request->Orders[0]->ForeignTransaction;
        $this->assertEquals('214498596', (string) $tx->accountFrom);
        $this->assertEquals('USD', (string) $tx->currency);
        $this->assertEquals('100.00', (string) $tx->amount);
        $this->assertEquals('IL600108570000006240047', (string) $tx->accountTo);
        $this->assertEquals('LUMIILIT', (string) $tx->bic);
        $this->assertEquals('0558', (string) $tx->ks);
        $this->assertEquals('1234567890', (string) $tx->vs);
        $this->assertEquals('0987654321', (string) $tx->ss);
        $this->assertEquals('2016-04-18', (string) $tx->date);
        $this->assertEquals('Comment', (string) $tx->comment);
        $this->assertEquals('470501', (string) $tx->detailsOfCharges);
        $this->assertEquals('110', (string) $tx->paymentReason);
        $this->assertEquals('Waste of money', (string) $tx->remittanceInfo1);
        $this->assertEquals('Beit Mait', (string) $tx->benefName);
        $this->assertEquals('7 Revivim Street', (string) $tx->benefStreet);
        $this->assertEquals('Givatayim', (string) $tx->benefCity);
        $this->assertEquals('IL', (string) $tx->benefCountry);
    }

    protected function setUp()
    {
        $builder = new InternationalPaymentBuilder();
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
        $mock->method('getCurrency')->willReturn('USD');
        $mock->method('getAmount')->willReturn(100.0);
        $mock->method('getAccountNumber')->willReturn('IL600108570000006240047');
        $mock->method('getBankCode')->willReturn('LUMIILIT');
        $mock->method('getConstantSymbol')->willReturn('0558');
        $mock->method('getVariableSymbol')->willReturn('1234567890');
        $mock->method('getSpecificSymbol')->willReturn('0987654321');
        $mock->method('getDate')->willReturn(new \DateTime('2016-04-18+0200'));
        $mock->method('getUserMessage')->willReturn('Groceries Foo, Inc.');
        $mock->method('getComment')->willReturn('Comment');
        $mock->method('getSpecification')->willReturn('470501');
        $mock->method('getTransactionType')->willReturn('110');
        $mock->method('getBenefName')->willReturn('Beit Mait');
        $mock->method('getBenefStreet')->willReturn('7 Revivim Street');
        $mock->method('getBenefCity')->willReturn('Givatayim');
        $mock->method('getBenefCountry')->willReturn('IL');
        $mock->method('getRemittanceInfo1')->willReturn('Waste of money');

        return $mock;
    }
}
