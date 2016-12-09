<?php
declare(strict_types = 1);

namespace FioApi;

class Account
{
    /** @var string */
    protected $accountNumber;

    /** @var string */
    protected $bankCode;

    /** @var string */
    protected $currency;

    /** @var string */
    protected $iban;

    /** @var string */
    protected $bic;

    public function __construct(
        string $accountNumber,
        string $bankCode,
        string $currency,
        string $iban,
        string $bic
    ) {
        $this->accountNumber = $accountNumber;
        $this->bankCode = $bankCode;
        $this->currency = $currency;
        $this->iban = $iban;
        $this->bic = $bic;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function getBic(): string
    {
        return $this->bic;
    }
}
