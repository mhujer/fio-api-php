<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

class PaymentOrderInternationalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider paymentOrderProvider
     */
    public function testPaymentOrderInternationalCorrectlyConvertsToArray(array $expected, PaymentOrderInternational $paymentOrder)
    {
        $this->assertSame($expected, $paymentOrder->toArray());
    }

    public function paymentOrderProvider(): array
    {
        return [
            'with all properties' => [
                [
                    'currency' => 'USD',
                    'amount' => 50.53,
                    'accountTo' => 'PK36SCBL0000001123456702',
                    'bic' => 'ALFHPKKAXXX',
                    'date' => '2021-07-22',
                    'comment' => 'comment',
                    'benefName' => 'Amir Khan',
                    'benefStreet' => 'Nishtar Rd 13',
                    'benefCity' => 'Karachi',
                    'benefCountry' => 'PK',
                    'remittanceInfo1' => 'info1',
                    'remittanceInfo2' => 'info2',
                    'remittanceInfo3' => 'info3',
                    'remittanceInfo4' => 'info4',
                    'detailsOfCharges' => 470502,
                    'paymentReason' => 952,
                ],
                new PaymentOrderInternational(
                    'USD',
                    50.53,
                    'PK36SCBL0000001123456702',
                    'ALFHPKKAXXX',
                    \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
                    'Amir Khan',
                    'Nishtar Rd 13',
                    'Karachi',
                    'PK',
                    470502,
                    952,
                    'info1',
                    'info2',
                    'info3',
                    'info4',
                    'comment'
                )
            ],
            'only with mandatory properties' => [
                [
                    'currency' => 'USD',
                    'amount' => 50.53,
                    'accountTo' => 'PK36SCBL0000001123456702',
                    'bic' => 'ALFHPKKAXXX',
                    'date' => '2021-07-22',
                    'comment' => null,
                    'benefName' => 'Amir Khan',
                    'benefStreet' => 'Nishtar Rd 13',
                    'benefCity' => 'Karachi',
                    'benefCountry' => 'PK',
                    'remittanceInfo1' => 'info1',
                    'remittanceInfo2' => null,
                    'remittanceInfo3' => null,
                    'remittanceInfo4' => null,
                    'detailsOfCharges' => 470502,
                    'paymentReason' => 952,
                ],
                new PaymentOrderInternational(
                    'USD',
                    50.53,
                    'PK36SCBL0000001123456702',
                    'ALFHPKKAXXX',
                    \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
                    'Amir Khan',
                    'Nishtar Rd 13',
                    'Karachi',
                    'PK',
                    470502,
                    952,
                    'info1'
                )
            ],
        ];
    }

    public function testInvalidPaymentReasonResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderInternational(
            'USD',
            50.53,
            'PK36SCBL0000001123456702',
            'ALFHPKKAXXX',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Amir Khan',
            'Nishtar Rd 13',
            'Karachi',
            'PK',
            470502,
            9998,
            'info1'
        );
    }

    public function testInvalidDetailsOfChargesResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderInternational(
            'USD',
            50.53,
            'PK36SCBL0000001123456702',
            'ALFHPKKAXXX',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Amir Khan',
            'Nishtar Rd 13',
            'Karachi',
            'PK',
            899999,
            952,
            'info1'
        );
    }

    public function testInvalidBenefNameResultsInUnexpectedPaymentOrderValueException()
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new PaymentOrderInternational(
            'USD',
            50.53,
            'PK36SCBL0000001123456702',
            'ALFHPKKAXXX',
            \DateTimeImmutable::createFromFormat('Y-m-d', '2021-07-22'),
            'Amiramiramiram Khankhan Khankhankhan',
            'Nishtar Rd 13',
            'Karachi',
            'PK',
            470502,
            952,
            'info1'
        );
    }
}
