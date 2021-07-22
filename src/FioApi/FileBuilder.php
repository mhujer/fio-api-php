<?php
declare(strict_types = 1);

namespace FioApi;

interface FileBuilder
{
    public static function getFileType(): string;
    public function createFromPaymentOrderList(PaymentOrderList $paymentOrderList, string $accountFrom): string;
}
