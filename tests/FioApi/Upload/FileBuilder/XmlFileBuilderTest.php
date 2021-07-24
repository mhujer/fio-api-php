<?php
declare(strict_types = 1);

namespace FioApi\Upload\FileBuilder;

use FioApi\Upload\Entity\PaymentOrderCzech;
use FioApi\Upload\Entity\PaymentOrderEuro;
use FioApi\Upload\Entity\PaymentOrderInternational;
use FioApi\Upload\Entity\PaymentOrderList;

class XmlFileBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testXmlFileBuilderCreatesCorrectXmlFromPaymentOrderList()
    {
        $paymentOrderCzech = new PaymentOrderCzech(
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
        );

        $paymentOrderEuro = new PaymentOrderEuro(
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
        );

        $paymentOrderInternational = new PaymentOrderInternational(
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
        );

        $paymentOrderList = new PaymentOrderList();
        $paymentOrderList->addPaymentOrder($paymentOrderEuro)
            ->addPaymentOrder($paymentOrderInternational)
            ->addPaymentOrder($paymentOrderCzech);

        $xmlFileBuilder = new XmlFileBuilder();

        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../data/example-request.xml',
            $xmlFileBuilder->createFromPaymentOrderList($paymentOrderList, '1234562')
        );
    }
}
