<?php
declare(strict_types = 1);

namespace FioApi;

class PaymentOrderList
{
    /** @var PaymentOrder[] */
    protected array $paymentOrders = [];

    public function addPaymentOrder(PaymentOrder $paymentOrder): void
    {
        $this->paymentOrders[] = $paymentOrder;
    }

    /**
     * @return PaymentOrder[]
     */
    public function getPaymentOrders(): array
    {
        return $this->paymentOrders;
    }

    public function isEmpty(): bool
    {
        return empty($this->paymentOrders);
    }

    public function clear(): void
    {
        $this->paymentOrders = [];
    }

}
