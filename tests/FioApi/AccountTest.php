<?php
declare(strict_types = 1);

namespace FioApi;

class AccountTest extends \PHPUnit\Framework\TestCase
{
    public function testAccountValuesAreProperlySet()
    {
        $account = new Account(
            '214498596',
            '2010',
            'CZK',
            'CZ9820100000000214498596',
            'FIOBCZPPXXX'
        );
        $this->assertSame('214498596', $account->getAccountNumber());
        $this->assertSame('2010', $account->getBankCode());
        $this->assertSame('CZK', $account->getCurrency());
        $this->assertSame('CZ9820100000000214498596', $account->getIban());
        $this->assertSame('FIOBCZPPXXX', $account->getBic());
    }
}
