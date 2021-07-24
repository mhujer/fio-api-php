<?php
declare(strict_types = 1);

namespace FioApi\Upload\FileBuilder;

use FioApi\Exceptions\UnexpectedPaymentOrderClassException;
use FioApi\Upload\Entity\PaymentOrder;
use FioApi\Upload\Entity\PaymentOrderCzech;
use FioApi\Upload\Entity\PaymentOrderEuro;
use FioApi\Upload\Entity\PaymentOrderInternational;
use FioApi\Upload\Entity\PaymentOrderList;
use XMLWriter;

class XmlFileBuilder implements FileBuilder
{
    protected ?XMLWriter $xml;

    // this array has to be sorted according to right order of payments types required by Fio API for XML files
    protected const PAYMENT_ORDER_TYPES_SORTED = [
        PaymentOrderCzech::class => 'DomesticTransaction',
        PaymentOrderEuro::class => 'T2Transaction',
        PaymentOrderInternational::class => 'ForeignTransaction',
    ];

    public function getFileType(): string
    {
        return 'xml';
    }

    public function createFromPaymentOrderList(PaymentOrderList $paymentOrderList, string $accountFrom): string
    {
        $segmentedArray = static::segmentPaymentOrdersByType($paymentOrderList);

        $this->createEmptyXml();

        foreach (self::PAYMENT_ORDER_TYPES_SORTED as $paymentOrderTypeForXml) {
            if (isset($segmentedArray[$paymentOrderTypeForXml])) {
                foreach ($segmentedArray[$paymentOrderTypeForXml] as $paymentOrder) {
                    static::createXmlFromPaymentOrder($paymentOrderTypeForXml, $paymentOrder, $accountFrom);
                }
            }
        }

        return $this->endDocument();
    }

    protected static function segmentPaymentOrdersByType(PaymentOrderList $paymentOrderList): array
    {
        $segmentedArray = [];
        foreach ($paymentOrderList->getPaymentOrders() as $paymentOrder) {
            $paymentOrderClassName = get_class($paymentOrder);
            $segmentName = self::PAYMENT_ORDER_TYPES_SORTED[$paymentOrderClassName] ?? null;
            if ($segmentName === null) {
                throw new UnexpectedPaymentOrderClassException(sprintf('Unknown payment order class "%s".', $paymentOrderClassName));
            }
            $segmentedArray[$segmentName][] = $paymentOrder;
        }
        return $segmentedArray;
    }

    protected function createEmptyXml(): void
    {
        $this->xml = new XMLWriter;
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->startElement('Import');
        $this->xml->writeAttribute('xmlns:xsi', 'https://www.w3.org/2001/XMLSchema-instance');
        $this->xml->writeAttribute('xsi:noNamespaceSchemaLocation', 'https://www.fio.cz/schema/importIB.xsd');
        $this->xml->startElement('Orders');
    }

    protected function createXmlFromPaymentOrder(string $paymentOrderType, PaymentOrder $paymentOrder, string $accountFrom): void
    {
        $this->xml->startElement($paymentOrderType);

        $this->xml->writeElement('accountFrom', $accountFrom);

        foreach ($paymentOrder->toArray() as $node => $value) {
            if ($value !== null) {
                $this->xml->writeElement($node, htmlspecialchars((string) $value));
            }
        }

        $this->xml->endElement();
    }

    protected function endDocument(): string
    {
        $this->xml->endDocument();
        $output = $this->xml->outputMemory();
        $this->xml = null;
        return $output;
    }
}
