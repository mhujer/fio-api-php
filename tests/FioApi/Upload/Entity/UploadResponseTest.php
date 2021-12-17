<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

class UploadResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testHasUploadSucceededReturnsTrueIfSuccessResponse()
    {
        $response = new UploadResponse(file_get_contents(__DIR__ . '/../data/example-response-success.xml'));

        $this->assertTrue($response->hasUploadSucceeded());
    }

    public function testHasUploadSucceededReturnsFalseIfErrorResponse()
    {
        $response = new UploadResponse(file_get_contents(__DIR__ . '/../data/example-response-error.xml'));

        $this->assertFalse($response->hasUploadSucceeded());
    }

    public function testGetCodeReturnsErrorCodeIfErrorResponse()
    {
        $response = new UploadResponse(file_get_contents(__DIR__ . '/../data/example-response-error.xml'));

        $this->assertSame(1, $response->getCode());
    }

    public function testGetIdInstructionReturnsInstructionIdIfSuccessResponse()
    {
        $response = new UploadResponse(file_get_contents(__DIR__ . '/../data/example-response-success.xml'));

        $this->assertSame(1385186, $response->getIdInstruction());
    }

    public function testGetIdInstructionReturnsNullIfErrorResponse()
    {
        $response = new UploadResponse(file_get_contents(__DIR__ . '/../data/example-response-error.xml'));

        $this->assertNull($response->getIdInstruction());
    }
}
