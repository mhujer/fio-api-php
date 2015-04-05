<?php
namespace FioApi;

use FioApi\Exceptions\MissingTokenException;

class UrlBuilder
{
    const BASE_URL = 'https://www.fio.cz/ib_api/rest/';

    /**
     * @var string
     */
    protected $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->setToken($token);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        if (!$token) {
            throw new MissingTokenException(
                'Token is required for ebanking API calls. You can get one at https://www.fio.cz/.'
            );
        }
        $this->token = $token;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return string
     */
    public function buildPeriodsUrl(\DateTime $from, \DateTime $to)
    {
        return sprintf(
            self::BASE_URL . 'periods/%s/%s/%s/transactions.json',
            $this->getToken(),
            $from->format('Y-m-d'),
            $to->format('Y-m-d')
        );
    }
}
