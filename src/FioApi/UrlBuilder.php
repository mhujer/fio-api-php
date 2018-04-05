<?php
declare(strict_types = 1);

namespace FioApi;

use FioApi\Exceptions\MissingTokenException;

class UrlBuilder
{
    const BASE_URL = 'https://www.fio.cz/ib_api/rest/';

    /** @var string */
    protected $token;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token)
    {
        if (!$token) {
            throw new MissingTokenException(
                'Token is required for ebanking API calls. You can get one at https://www.fio.cz/.'
            );
        }
        $this->token = $token;
    }

    public function buildPeriodsUrl(\DateTimeInterface $from, \DateTimeInterface $to): string
    {
        return sprintf(
            self::BASE_URL . 'periods/%s/%s/%s/transactions.json',
            $this->getToken(),
            $from->format('Y-m-d'),
            $to->format('Y-m-d')
        );
    }

    public function buildLastUrl(): string
    {
        return sprintf(
            self::BASE_URL . 'last/%s/transactions.json',
            $this->getToken()
        );
    }

    public function buildSetLastIdUrl(string $id): string
    {
        return sprintf(
            self::BASE_URL . 'set-last-id/%s/%s/',
            $this->getToken(),
            $id
        );
    }
}
