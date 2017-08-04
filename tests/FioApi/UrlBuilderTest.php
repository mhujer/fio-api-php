<?php
declare(strict_types = 1);

namespace FioApi;

class UrlBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testMissingTokenExceptionIsThrownForEmptyToken()
    {
        $this->expectException(\FioApi\Exceptions\MissingTokenException::class);

        new UrlBuilder('');
    }

    public function testTokenCanBeSetThroughConstructor()
    {
        $urlBuilder = new UrlBuilder('token1');
        $this->assertSame('token1', $urlBuilder->getToken());
    }

    public function testTokenCanBeChangedThroughSetter()
    {
        $urlBuilder = new UrlBuilder('token1');
        $urlBuilder->setToken('token2');
        $this->assertSame('token2', $urlBuilder->getToken());
    }

    public function testBuildPeriodsUrlReturnValidUrl()
    {
        $urlBuilder = new UrlBuilder('token1');
        $this->assertSame(
            'https://www.fio.cz/ib_api/rest/periods/token1/2015-03-25/2015-03-31/transactions.json',
            $urlBuilder->buildPeriodsUrl(
                new \DateTimeImmutable('2015-03-25'),
                new \DateTimeImmutable('2015-03-31')
            )
        );
    }
}
