<?php
declare(strict_types = 1);

namespace FioApi;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
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
        $container = [];
        $history = Middleware::history($container);

        $handler = HandlerStack::create(new MockHandler([
            new Response(200),
        ]));
        $handler->push($history);
        $downloader = new Downloader('validToken', new Client(['handler' => $handler]));

        $downloader->setLastId('123456');

        $this->assertCount(1, $container);

        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = $container[0]['request'];

        $this->assertSame('https://www.fio.cz/ib_api/rest/set-last-id/validToken/123456/', (string) $request->getUri());
    }

    public function testDownloaderSetCertificatePath()
    {
        $downloader = new Downloader('validToken');
        $downloader->setCertificatePath('foo.pem');
        $this->assertSame('foo.pem', $downloader->getCertificatePath());
    }
}
