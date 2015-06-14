<?php

namespace FioApi;

use FioApi\Exceptions;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \FioApi\Exceptions\TooGreedyException
     */
    public function testNotRespectingTheTimeoutResultsInTooGreedyException()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(409),
        ]));
        $downloader = new Downloader('testToken', new Client(['handler' => $handler]));
        $downloader->downloadSince(new \DateTime('-1 week'));
    }

    /**
     * @expectedException \FioApi\Exceptions\InternalErrorException
     */
    public function testInvalidTokenResultsInInternalErrorException()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(500),
        ]));
        $downloader = new Downloader('invalidToken', new Client(['handler' => $handler]));
        $downloader->downloadSince(new \DateTime('-1 week'));
    }

    /**
     * @expectedException \GuzzleHttp\Exception\BadResponseException
     * @expectedExceptionCode 418
     */
    public function testUnknownResponseCodePassesOriginalException()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(418),
        ]));
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));
        $downloader->downloadSince(new \DateTime('-1 week'));
    }

    public function testDownloaderDownloadsData()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/data/example-response.json')),
        ]));
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));
        $transactionList = $downloader->downloadSince(new \DateTime('-1 week'));
        $this->assertInstanceOf('\FioApi\TransactionList', $transactionList);
    }

    public function testDownloaderSetCertificatePath()
    {
        $downloader = new Downloader('validToken');
        $downloader->setCertificatePath('foo.pem');
        $this->assertEquals('foo.pem', $downloader->getCertificatePath());
    }
}
