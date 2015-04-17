<?php

namespace FioApi;

use FioApi\Exceptions;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Subscriber\Mock;

class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \FioApi\Exceptions\TooGreedyException
     */
    public function testNotRespectingTheTimeoutResultsInTooGreedyException()
    {
        $downloader = new Downloader('testToken');
        $downloader->getClient()->getEmitter()->attach(new Mock([
            new Response(409),
        ]));

        $downloader->downloadSince(new \DateTime('-1 week'));
    }

    /**
     * @expectedException \FioApi\Exceptions\InternalErrorException
     */
    public function testInvalidTokenResultsInInternalErrorException()
    {
        $downloader = new Downloader('invalidToken');
        $downloader->getClient()->getEmitter()->attach(new Mock([
            new Response(500),
        ]));

        $downloader->downloadSince(new \DateTime('-1 week'));
    }

    /**
     * @expectedException \GuzzleHttp\Exception\BadResponseException
     * @expectedExceptionCode 418
     * @expectedExceptionMessage Client error response [url] https://www.fio.cz/ib_api/rest/periods/validToken/2015-04-10/2015-04-17/transactions.json [status code] 418 [reason phrase]
     */
    public function testUnknownResponseCodePassesOriginalException()
    {
        $downloader = new Downloader('validToken');
        $downloader->getClient()->getEmitter()->attach(new Mock([
            new Response(418),
        ]));
        $downloader->downloadSince(new \DateTime('-1 week'));
    }

    public function testDownloaderDownloadsData()
    {
        $downloader = new Downloader('validToken');
        $downloader->getClient()->getEmitter()->attach(new Mock([
            new Response(200, [], Stream::factory(file_get_contents(__DIR__ . '/data/example-response.json'))),
        ]));

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
