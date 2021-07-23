<?php
declare(strict_types = 1);

namespace FioApi\Download;

use FioApi\Download\Entity\TransactionList;
use FioApi\Exceptions\InternalErrorException;
use FioApi\Exceptions\TooGreedyException;
use FioApi\Transferrer;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Downloader extends Transferrer
{
    public function __construct(
        string $token,
        ClientInterface $client = null
    ) {
        parent::__construct($token, $client);
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
            $client->request('get', $url, ['verify' => $this->getCertificatePath()]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $this->handleException($e);
        }
    }

    private function downloadTransactionsList(string $url): TransactionList
    {
        $client = $this->getClient();

        try {
            /** @var ResponseInterface $response */
            $response = $client->request('get', $url, ['verify' => $this->getCertificatePath()]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $this->handleException($e);
        }

        return TransactionList::create(json_decode($response->getBody()->getContents())->accountStatement);
    }

    private function handleException(\GuzzleHttp\Exception\BadResponseException $e): void
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
