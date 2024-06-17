<?php
declare(strict_types = 1);

namespace FioApi;

use Composer\CaBundle\CaBundle;
use FioApi\Exceptions\InternalErrorException;
use FioApi\Exceptions\TooGreedyException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class Downloader
{
    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var ?ClientInterface */
    protected $client;

    public function __construct(string $token, ClientInterface $client = null)
    {
        $this->urlBuilder = new UrlBuilder($token);
        $this->client = $client;
    }

    public function getClient(): ClientInterface
    {
        if ($this->client === null) {
            $this->client = new Client([
                RequestOptions::VERIFY => CaBundle::getSystemCaRootBundlePath()
            ]);
        }
        return $this->client;
    }

    public function downloadFromTo(\DateTimeInterface $from, \DateTimeInterface $to): TransactionList
    {
        $url = $this->urlBuilder->buildPeriodsUrl($from, $to);
        return $this->downloadTransactionsList($url);
    }

    public function downloadSince(\DateTimeInterface $since): TransactionList
    {
        return $this->downloadFromTo($since, new \DateTimeImmutable());
    }

    public function downloadLast(): TransactionList
    {
        $url = $this->urlBuilder->buildLastUrl();
        return $this->downloadTransactionsList($url);
    }

    public function setLastId(string $id): void
    {
        $client = $this->getClient();
        $url = $this->urlBuilder->buildSetLastIdUrl($id);

        try {
            $client->request('get', $url);
        } catch (BadResponseException $e) {
            $this->handleException($e);
        }
    }

    private function downloadTransactionsList(string $url): TransactionList
    {
        $client = $this->getClient();
        $transactions = null;

        try {
            $response = $client->request('get', $url);
            $jsonData = json_decode($response->getBody()->getContents(), null, 512, JSON_THROW_ON_ERROR);
            $transactions = $jsonData->accountStatement;
        } catch (BadResponseException $e) {
            $this->handleException($e);
        }

        return TransactionList::create($transactions);
    }

    private function handleException(BadResponseException $e): void
    {
        if ($e->getCode() == 409) {
            throw new TooGreedyException('You can use one token for API call every 30 seconds', $e->getCode(), $e);
        }
        if ($e->getCode() == 500) {
            throw new InternalErrorException(
                'Server returned 500 Internal Error (probably invalid token?)',
                $e->getCode(),
                $e
            );
        }
        throw $e;
    }
}
