<?php

namespace FioApi;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class UploaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSendRequest()
    {
        $request = simplexml_load_file(__DIR__.'/data/01-valid-request.xml');
        $handler = HandlerStack::create(new MockHandler([
           new Response(200, [], file_get_contents(__DIR__.'/data/01-valid-response.xml')),
        ]));
        $client = new Client(['handler' => $handler]);
        $uploader = new Uploader('token', $client);

        $response = $uploader->sendRequest($request);
        $this->assertInstanceOf('\FioApi\ImportResponse', $response);
    }
}
