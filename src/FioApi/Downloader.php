<?php
namespace FioApi;

use FioApi\Exceptions\InternalErrorException;
use FioApi\Exceptions\TooGreedyException;
use Psr\Http\Message\ResponseInterface;

class Downloader extends AbstractClient
{
    /**
     * @param \DateTime $from
     * @param \DateTime $to
     *
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
     *
     * @return TransactionList
     */
    public function downloadSince(\DateTime $since)
    {
        return $this->downloadFromTo($since, new \DateTime());
    }
}
