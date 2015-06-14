<?php
namespace FioApi;

use FioApi\Exceptions\InternalErrorException;
use FioApi\Exceptions\TooGreedyException;
use GuzzleHttp\Client as Guzzle;

class Downloader
{
    /** @var UrlBuilder */
    protected $urlBuilder;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $certificatePath;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->urlBuilder = new UrlBuilder($token);
    }

    /**
     * @param string $path
     */
    public function setCertificatePath($path)
    {
        $this->certificatePath = $path;
    }

    public function getCertificatePath()
    {
        if ($this->certificatePath) {
            return $this->certificatePath;
        }

        //Key downloaded from https://www.geotrust.com/resources/root-certificates/
        return __DIR__ . '/keys/GeoTrust_Primary_CA.pem';
    }

    /**
     * @return Guzzle
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Guzzle();
        }
        return $this->client;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return TransactionList
     */
    public function downloadFromTo(\DateTime $from, \DateTime $to)
    {
        $client = $this->getClient();
        $url = $this->urlBuilder->buildPeriodsUrl($from, $to);

        try {
            $response = $client->get($url, ['verify' => $this->getCertificatePath()]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
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

        return TransactionList::create($response->json([
            'object' => true,
            'big_int_strings' => true,
        ])->accountStatement);
    }

    /**
     * @param \DateTime $since
     * @return TransactionList
     */
    public function downloadSince(\DateTime $since)
    {
        return $this->downloadFromTo($since, new \DateTime());
    }
}
