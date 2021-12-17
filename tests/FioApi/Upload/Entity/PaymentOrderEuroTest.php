<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

class PaymentOrderEuroTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider paymentOrderProvider
     */
    public function testPaymentOrderEuroCorrectlyConvertsToArray(array $expected, PaymentOrderEuro $paymentOrder)
    {
        $this->assertSame($expected, $paymentOrder->toArray());
    }

    public function paymentOrderProvider(): array
    {
        return [
            'with all properties' => [
                [
                    'currency' => 'EUR',
                    'amount' => 50.53,
                    'accountTo' => 'AT611904300234573201',
                    'ks' => '0558',
                    'vs' => '1234567890',
                    'ss' => '0987654321',
                    'bic' => 'ABAGATWWXXX',
                    'date' => '2021-07-22',
                    'comment' => 'comment',
                    'benefName' => 'Hans Gruber',
                    'benefStreet' => 'Gugitzgasse 2',
                    'benefCity' => 'Wien',
                    'benefCountry' => 'AT',
                    'remittanceInfo1' => 'info1',
                    'remittanceInfo2' => 'info2',
                    'remittanceInfo3' => 'info3',
                    'paymentReason' => 520,
                    'paymentType' => 431008,
                ],
                new PaymentOrderEuro(
                    'EUR',
                    50.53,
                    'AT611904300234573201',
                    \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
                    'Hans Gruber',
                    'Gugitzgasse 2',
                    'Wien',
                    'AT',
                    '0558',
                    '1234567890',
                    '0987654321',
                    'ABAGATWWXXX',
                    'comment',
                    'info1',
                    'info2',
                    'info3',
                    520,
                    431008
                )
            ],
            'only with mandatory properties' => [
                [
                    'currency' => 'EUR',
                    'amount' => 50.53,
                    'accountTo' => 'AT611904300234573201',
                    'ks' => null,
                    'vs' => null,
                    'ss' => null,
                    'bic' => null,
                    'date' => '2021-07-22',
                    'comment' => null,
                    'benefName' => 'Hans Gruber',
                    'benefStreet' => null,
                    'benefCity' => null,
                    'benefCountry' => null,
                    'remittanceInfo1' => null,
                    'remittanceInfo2' => null,
                    'remittanceInfo3' => null,
                    'paymentReason' => null,
                    'paymentType' => null,
                ],
                new PaymentOrderEuro(
                    'EUR',
                    50.53,
                    'AT611904300234573201',
                    \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
                    'Hans Gruber'
                )
            ],
        ];
    }

    public function testInvalidCurrencyResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderEuro(
            'EURO',
            50.53,
            'AT611904300234573201',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Hans Gruber'
        );
    }

    public function testInvalidAccountToResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderEuro(
            'EUR',
            50.53,
            'AT611904300234573201904300234573201',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Hans Gruber'
        );
    }

    /**
     * @dataProvider bicProvider
     */
    public function testInvalidBicResultsInUnexpectedPaymentOrderValueException(string $bic)
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderEuro(
            'EUR',
            50.53,
            'AT611904300234573201',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Hans Gruber',
            'Gugitzgasse 2',
            'Wien',
            'AT',
            '0558',
            '1234567890',
            '0987654321',
            $bic
        );
    }

    public function bicProvider(): array
    {
        return [
            'not only alnum characters' => [ 'A-AGATWWXXX' ],
            'wrong length' => [ 'XABAGATWWXXX' ],
        ];
    }

    /**
     * @dataProvider benefCountryProvider
     */
    public function testInvalidBenefCountryResultsInUnexpectedPaymentOrderValueException(string $benefCountry)
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderEuro(
            'EUR',
            50.53,
            'AT611904300234573201',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Hans Gruber',
            'Gugitzgasse 2',
            'Wien',
            $benefCountry
        );
    }

    public function benefCountryProvider(): array
    {
        return [
            'not only alnum characters' => [ 'A-' ],
            'too long' => [ 'AUST' ],
        ];
    }
}
