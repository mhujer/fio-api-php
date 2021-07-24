<?php
declare(strict_types = 1);

namespace FioApi\Upload;

use FioApi\Exceptions\MissingPaymentOrderException;
use FioApi\Exceptions\UnexpectedPaymentOrderValueException;
use FioApi\Upload\Entity\PaymentOrderCzech;
use FioApi\Upload\Entity\UploadResponse;
use FioApi\Upload\FileBuilder\FileBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class UploaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider accountFromProvider
     */
    public function testInvalidAccountFromResultsInUnexpectedPaymentOrderValueException(string $accountFrom)
    {
        $this->expectException(UnexpectedPaymentOrderValueException::class);

        new Uploader('testToken', $accountFrom);
    }

    public function accountFromProvider(): array
    {
        return [
            'not only digits' => [ '12345489x' ],
            'too long' => [ '12345678901234567' ],
        ];
    }

    public function testAddPaymentOrderToUploader()
    {
        $uploader = new Uploader('testToken', '123456489');
        $uploader->addPaymentOrder($this->createStub(PaymentOrderCzech::class));

        $this->assertFalse($uploader->getPaymentOrderList()->isEmpty());
    }

    public function testUploadingWithoutPaymentOrderResultsInMissingPaymentOrderException()
    {
        $uploader = new Uploader('testToken', '123456489');

        $this->expectException(MissingPaymentOrderException::class);

        $uploader->uploadPaymentOrders();
    }

    public function testUploaderUploadsPaymentOrders()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/data/example-response-success.xml')),
        ]));
        $uploader = new Uploader(
            'testToken',
            '123456489',
            new Client(['handler' => $handler]),
            $this->createStub(FileBuilder::class)
        );
        $uploader->addPaymentOrder($this->createStub(PaymentOrderCzech::class));
        $response = $uploader->uploadPaymentOrders();

        $this->assertInstanceOf(UploadResponse::class, $response);

        return $uploader;
    }

    /**
     * @depends testUploaderUploadsPaymentOrders
     */
    public function testUploaderClearPaymentOrdersAfterUpload(Uploader $uploader)
    {
        $this->assertTrue($uploader->getPaymentOrderList()->isEmpty());
    }
}
