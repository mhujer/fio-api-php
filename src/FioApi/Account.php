<?php
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

    /**
     * @param string $accountNumber
     * @param string $bankCode
     * @param string $currency
     * @param string $iban
     * @param string $bic
     */
    public function __construct($accountNumber, $bankCode, $currency, $iban, $bic)
    {
        $this->accountNumber = $accountNumber;
        $this->bankCode = $bankCode;
        $this->currency = $currency;
        $this->iban = $iban;
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }
}
