<?php

namespace FioApi;

class DomesticPaymentBuilder implements PaymentBuilder
{
    /**
     * Build XML request to import payments.
     *
     * @param Account $account
     * @param array   $transactions Transaction[]
     *
     * @return \SimpleXMLElement
     */
    public function build(Account $account, array $transactions)
    {
        $request = $this->createRoot();
        $orders = $request->addChild('Orders');
        foreach ($transactions as $transaction) {
            $node = $orders->addChild('DomesticTransaction');
            $this->createTransaction($node, $transaction, $account);
        }

        return $request->asXML();
    }

    protected function createTransaction(\SimpleXMLElement $node, Transaction $tx, Account $account)
    {
        $nodes = [
            'accountFrom'         => $account->getAccountNumber(),
            'currency'            => $tx->getCurrency(),
            'amount'              => number_format($tx->getAmount(), 2),
            'accountTo'           => $tx->getAccountNumber(),
            'bankCode'            => $tx->getBankCode(),
            'ks'                  => $tx->getConstantSymbol(),
            'vs'                  => $tx->getVariableSymbol(),
            'ss'                  => $tx->getSpecificSymbol(),
            'date'                => $tx->getDate()->format('Y-m-d'),
            'messageForRecipient' => $tx->getUserMessage(),
            'comment'             => $tx->getComment(),
            'paymentType'         => $tx->getSpecification(),
        ];

        foreach ($nodes as $el => $value) {
            if (!empty($value)) {
                $node->addChild($el, $value);
            }
        }
    }

    /**
     * Create root element of XML document.
     *
     * @return \SimpleXMLElement
     */
    protected function createRoot()
    {
        $root = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><Import />');
        $root->addAttribute('xmlns:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-Instance');

        return $root;
    }
}
