<?php
declare(strict_types = 1);

namespace FioApi;

class UrlBuilderTest extends \PHPUnit\Framework\TestCase
{
    public function testMissingTokenExceptionIsThrownForEmptyToken(): void
    {
        $this->expectException(\FioApi\Exceptions\MissingTokenException::class);

        new UrlBuilder('');
    }

    public function testTokenCanBeSetThroughConstructor(): void
    {
        $urlBuilder = new UrlBuilder('token1');
        $this->assertSame('token1', $urlBuilder->getToken());
    }

    public function testTokenCanBeChangedThroughSetter(): void
    {
        $urlBuilder = new UrlBuilder('token1');
        $urlBuilder->setToken('token2');
        $this->assertSame('token2', $urlBuilder->getToken());
    }

    public function testBuildPeriodsUrlReturnValidUrl(): void
    {
        $urlBuilder = new UrlBuilder('token1');
        $this->assertSame(
            'https://fioapi.fio.cz/v1/rest/periods/token1/2015-03-25/2015-03-31/transactions.json',
            $urlBuilder->buildPeriodsUrl(
                new \DateTimeImmutable('2015-03-25'),
                new \DateTimeImmutable('2015-03-31')
            )
        );
    }
}
