<?php
declare(strict_types = 1);

namespace FioApi;

use PHPUnit\Framework\Assert;

class AccountTest extends \PHPUnit\Framework\TestCase
{
    public function testAccountValuesAreProperlySet(): void
    {
        $account = new Account(
            '214498596',
            '2010',
            'CZK',
            'CZ9820100000000214498596',
            'FIOBCZPPXXX'
        );
        Assert::assertSame('214498596', $account->getAccountNumber());
        Assert::assertSame('2010', $account->getBankCode());
        Assert::assertSame('CZK', $account->getCurrency());
        Assert::assertSame('CZ9820100000000214498596', $account->getIban());
        Assert::assertSame('FIOBCZPPXXX', $account->getBic());
    }
}
