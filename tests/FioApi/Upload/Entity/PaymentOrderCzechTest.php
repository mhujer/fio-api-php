<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

class PaymentOrderCzechTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider paymentOrderProvider
     */
    public function testPaymentOrderCzechCorrectlyConvertsToArray(array $expected, PaymentOrderCzech $paymentOrder)
    {
        $this->assertSame($expected, $paymentOrder->toArray());
    }

    public function paymentOrderProvider(): array
    {
        return [
            'with all properties' => [
                [
                    'currency' => 'CZK',
                    'amount' => 100.0,
                    'accountTo' => '2212-2000000699',
                    'bankCode' => '0300',
                    'ks' => '0558',
                    'vs' => '1234567890',
                    'ss' => '0987654321',
                    'date' => '2021-07-22',
                    'messageForRecipient' => 'message',
                    'comment' => 'comment',
                    'paymentReason' => 110,
                    'paymentType' => 431001,
                ],
                new PaymentOrderCzech(
                    'CZK',
                    100.0,
                    '2212-2000000699',
                    '0300',
                    \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
                    '0558',
                    '1234567890',
                    '0987654321',
                    'message',
                    'comment',
                    110,
                    431001
                )
            ],
            'only with mandatory properties' => [
                [
                    'currency' => 'CZK',
                    'amount' => 100.0,
                    'accountTo' => '2212-2000000699',
                    'bankCode' => '0300',
                    'ks' => null,
                    'vs' => null,
                    'ss' => null,
                    'date' => '2021-07-22',
                    'messageForRecipient' => null,
                    'comment' => null,
                    'paymentReason' => null,
                    'paymentType' => null,
                ],
                new PaymentOrderCzech(
                    'CZK',
                    100.0,
                    '2212-2000000699',
                    '0300',
                    \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22')
                )
            ],
        ];
    }

    public function testInvalidAmountResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderCzech(
            'CZK',
            -100.0,
            '22122000000699',
            '0300',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22')
        );
    }

    public function testInvalidAccountToResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderCzech(
            'CZK',
            100.0,
            '22122000000699',
            '0300',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22')
        );
    }

    /**
     * @dataProvider bankCodeProvider
     */
    public function testInvalidBankCodeResultsInUnexpectedPaymentOrderValueException(string $bankCode)
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderCzech(
            'CZK',
            100.0,
            '2212-2000000699',
            $bankCode,
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22')
        );
    }

    public function bankCodeProvider(): array
    {
        return [
            'not only digits' => [ '030x' ],
            'too long' => [ '03000' ],
        ];
    }

    /**
     * @dataProvider variableSymbolProvider
     */
    public function testInvalidVariableSymbolResultsInUnexpectedPaymentOrderValueException(string $variableSymbol)
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

         new PaymentOrderCzech(
            'CZK',
            100.0,
            '2212-2000000699',
            '0300',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            '0558',
             $variableSymbol
        );
    }

    public function variableSymbolProvider(): array
    {
        return [
            'not only digits' => [ '123456789x' ],
            'too long' => [ '1234567890123' ],
        ];
    }
}
