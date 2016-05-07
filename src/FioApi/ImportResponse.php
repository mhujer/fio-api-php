<?php

namespace FioApi;

/**
 * Class represents response from FIO api on XML import request.
 *
 * @author Petr Kramar <petr.kramar@perlur.cz>
 * @license MIT
 */
class ImportResponse
{
    /**
     * @var \SimpleXMLElement
     */
    private $xml;

    public function __construct($xml)
    {
        $this->xml = simplexml_load_string($xml);
    }

    /**
     * Get error code.
     *
     * @return int
     */
    public function getErrorCode()
    {
        return (int) $this->xml->result->errorCode;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return (string) $this->xml->result->status;
    }

    /**
     * Get debit sum of the batch for particular currency.
     *
     * @param string $currency
     *
     * @return float
     */
    public function getDebitSum($currency = 'CZK')
    {
        return (float) $this->xml->result->sums->xpath("sum[@id = '{$currency}']")[0]->sumDebet;
    }

    /**
     * Get credit sum of the batch for particular currency.
     *
     * @param string $currency
     *
     * @return float
     */
    public function getCreditSum($currency = 'CZK')
    {
        return (float) $this->xml->result->sums->xpath("sum[@id = '{$currency}']")[0]->sumCredit;
    }
}
