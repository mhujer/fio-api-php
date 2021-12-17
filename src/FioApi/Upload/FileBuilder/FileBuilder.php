<?php
declare(strict_types = 1);

namespace FioApi\Upload\FileBuilder;

use FioApi\Upload\Entity\PaymentOrderList;

interface FileBuilder
{
    public function getFileType(): string;
    public function createFromPaymentOrderList(PaymentOrderList $paymentOrderList, string $accountFrom): string;
}
