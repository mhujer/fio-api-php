<?php

namespace FioApi;

class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \FioApi\Exceptions\MissingTokenException
     */
    public function testMissingTokenExceptionIsThrownForEmptyToken()
    {
        new UrlBuilder('');
    }

    public function testTokenCanBeSetThroughConstructor()
    {
        $urlBuilder = new UrlBuilder('token1');
        $this->assertEquals('token1', $urlBuilder->getToken());
    }

    public function testTokenCanBeChangedThroughSetter()
    {
        $urlBuilder = new UrlBuilder('token1');
        $urlBuilder->setToken('token2');
        $this->assertEquals('token2', $urlBuilder->getToken());
    }

    public function testBuildPeriodsUrlReturnValidUrl()
    {
        $urlBuilder = new UrlBuilder('token1');
        $this->assertEquals(
            'https://fioapi.fio.cz/v1/rest/periods/token1/2015-03-25/2015-03-31/transactions.json',
            $urlBuilder->buildPeriodsUrl(new \DateTime('2015-03-25'), new \DateTime('2015-03-31'))
        );
    }
}
