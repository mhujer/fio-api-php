<?php
namespace FioApi;

class ImportResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleOkResponse()
    {
        $response = new ImportResponse(file_get_contents(__DIR__ . '/data/01-valid-response.xml'));

        $this->assertEquals(0, $response->getErrorCode());
        $this->assertEquals('ok', $response->getStatus());
        $this->assertEquals(200.0, $response->getDebitSum('CZK'));
        $this->assertEquals(0.0, $response->getCreditSum('CZK'));
    }

    public function testMultipleOkReponses()
    {
        $response = new ImportResponse(file_get_contents(__DIR__ . '/data/02-valid-multiple-response.xml'));

        $this->assertEquals(0, $response->getErrorCode());
        $this->assertEquals('ok', $response->getStatus());
        $this->assertEquals(250.0, $response->getDebitSum('CZK'));
        $this->assertEquals(0.0, $response->getCreditSum('CZK'));
    }
}
