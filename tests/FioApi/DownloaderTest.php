<?php
declare(strict_types = 1);

namespace FioApi;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class DownloaderTest extends \PHPUnit\Framework\TestCase
{
    public function testNotRespectingTheTimeoutResultsInTooGreedyException()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(409),
        ]));
        $downloader = new Downloader('testToken', new Client(['handler' => $handler]));

        $this->expectException(\FioApi\Exceptions\TooGreedyException::class);

        $downloader->downloadSince(new \DateTimeImmutable('-1 week'));
    }

    public function testInvalidTokenResultsInInternalErrorException()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(500),
        ]));
        $downloader = new Downloader('invalidToken', new Client(['handler' => $handler]));

        $this->expectException(\FioApi\Exceptions\InternalErrorException::class);

        $downloader->downloadSince(new \DateTimeImmutable('-1 week'));
    }

    public function testUnknownResponseCodePassesOriginalException()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(418),
        ]));
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));

        $this->expectException(\GuzzleHttp\Exception\BadResponseException::class);
        $this->expectExceptionCode(418);

        $downloader->downloadSince(new \DateTimeImmutable('-1 week'));
    }

    public function testDownloaderDownloadsData()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/data/example-response.json')),
        ]));
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));

        $transactionList = $downloader->downloadSince(new \DateTimeImmutable('-1 week'));

        $this->assertInstanceOf(TransactionList::class, $transactionList);
    }

    public function testDownloaderDownloadsLast()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/data/example-response.json')),
        ]));
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));

        $transactionList = $downloader->downloadLast();

        $this->assertInstanceOf(TransactionList::class, $transactionList);
    }

    public function testDownloaderSetsLastId()
    {
        $handler = HandlerStack::create(new MockHandler([
            new Response(200),
        ]));
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));

        $downloader->setLastId('123456');
        $this->addToAssertionCount(1);
    }

    public function testDownloaderSetCertificatePath()
    {
        $downloader = new Downloader('validToken');
        $downloader->setCertificatePath('foo.pem');
        $this->assertSame('foo.pem', $downloader->getCertificatePath());
    }
}
