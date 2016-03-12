<?php
namespace FioApi;

use FioApi\Exceptions\InternalErrorException;
use FioApi\Exceptions\TooGreedyException;
use Psr\Http\Message\ResponseInterface;

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
    public function __construct($token, \GuzzleHttp\ClientInterface $client = null)
    {
        $this->urlBuilder = new UrlBuilder($token);
        $this->client = $client;
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
        return __DIR__ . '/keys/Geotrust_PCA_G3_Root.pem';
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new \GuzzleHttp\Client();
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
            /** @var ResponseInterface $response */
            $response = $client->request('get', $url, ['verify' => $this->getCertificatePath()]);
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

        return TransactionList::create(json_decode($response->getBody())->accountStatement);
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
