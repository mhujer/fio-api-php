<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

class PaymentOrderList
{
    /** @var PaymentOrder[] */
    protected array $paymentOrders = [];

    /**
     * @return static
     */
    public function addPaymentOrder(PaymentOrder $paymentOrder)
    {
        $this->paymentOrders[] = $paymentOrder;
        return $this;
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
