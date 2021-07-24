<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

class PaymentOrderListTest extends \PHPUnit\Framework\TestCase
{
    public function testAddPaymentOrderToPaymentOrderList()
    {
        $paymentOrderList = new PaymentOrderList();
        $paymentOrderList->addPaymentOrder($this->createStub(PaymentOrderCzech::class));

        $this->assertInstanceOf(PaymentOrderCzech::class, $paymentOrderList->getPaymentOrders()[0]);

        return $paymentOrderList;
    }

    public function testIsEmptyReturnsTrueIfNoPaymentOrders()
    {
        $paymentOrderList = new PaymentOrderList();

        $this->assertTrue($paymentOrderList->isEmpty());
    }

    /**
     * @depends testAddPaymentOrderToPaymentOrderList
     */
    public function testIsEmptyReturnsFalseIfPaymentOrderAlreadyAdded(PaymentOrderList $paymentOrderList)
    {
        $this->assertFalse($paymentOrderList->isEmpty());
    }

    /**
     * @depends testAddPaymentOrderToPaymentOrderList
     */
    public function testClearDeleteAllPaymentOrders(PaymentOrderList $paymentOrderList)
    {
        $paymentOrderList->clear();

        $this->assertEmpty($paymentOrderList->getPaymentOrders());
    }
}
