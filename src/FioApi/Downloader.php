<?php
declare(strict_types = 1);

namespace FioApi;

use FioApi\Exceptions\InternalErrorException;
use FioApi\Exceptions\TooGreedyException;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Downloader
{
    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $certificatePath;

    public function __construct(
        string $token,
        \GuzzleHttp\ClientInterface $client = null
    ) {
        $this->urlBuilder = new UrlBuilder($token);
        $this->client = $client;
    }

    public function setCertificatePath(string $path)
    {
        $this->certificatePath = $path;
    }

    public function getCertificatePath(): string
    {
        if ($this->certificatePath) {
            return $this->certificatePath;
        }

        if (class_exists('\Composer\CaBundle\CaBundle')) {
            return \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath();
        } elseif (class_exists('\Kdyby\CurlCaBundle\CertificateHelper')) {
            return \Kdyby\CurlCaBundle\CertificateHelper::getCaInfoFile();
        }

        //Key downloaded from https://www.geotrust.com/resources/root-certificates/
        return __DIR__ . '/keys/Geotrust_PCA_G3_Root.pem';
    }

    public function getClient(): ClientInterface
    {
        if (!$this->client) {
            $this->client = new \GuzzleHttp\Client();
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
