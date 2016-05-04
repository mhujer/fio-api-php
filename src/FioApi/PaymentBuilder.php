<?php
namespace FioApi;

interface PaymentBuilder
{
    /**
     * Build XML request to import payments.
     *
     * @param Account $account
     * @param array   $transactions Transaction[]
     *
     * @return \SimpleXMLElement
     */
    public function build(Account $account, array $transactions);
}
