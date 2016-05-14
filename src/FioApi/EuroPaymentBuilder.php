<?php

namespace FioApi;

class EuroPaymentBuilder extends AbstractPaymentBuilder implements PaymentBuilder
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
            $node = $orders->addChild('T2Transaction');
            $this->createTransaction($node, $transaction, $account);
        }

        return $request;
    }

    protected function createTransaction(\SimpleXMLElement $node, Transaction $tx, Account $account)
    {
        $nodes = [
            'accountFrom'     => $account->getAccountNumber(),
            'currency'        => $tx->getCurrency(),
            'amount'          => number_format($tx->getAmount(), 2),
            'accountTo'       => $tx->getAccountNumber(),
            'ks'              => $tx->getConstantSymbol(),
            'vs'              => $tx->getVariableSymbol(),
            'ss'              => $tx->getSpecificSymbol(),
            'bic'             => $tx->getBankCode(),
            'date'            => $tx->getDate()->format('Y-m-d'),
            'comment'         => $tx->getComment(),
            'benefName'       => $tx->getBenefName(),
            'benefStreet'     => $tx->getBenefStreet(),
            'benefCity'       => $tx->getBenefCity(),
            'benefCountry'    => $tx->getBenefCountry(),
            'remittanceInfo1' => $tx->getRemittanceInfo1(),
            'remittanceInfo2' => $tx->getRemittanceInfo2(),
            'remittanceInfo3' => $tx->getRemittanceInfo3(),
            'paymentType'     => $tx->getSpecification(),
        ];

        foreach ($nodes as $el => $value) {
            if (!empty($value)) {
                $node->addChild($el, $value);
            }
        }
    }
}
