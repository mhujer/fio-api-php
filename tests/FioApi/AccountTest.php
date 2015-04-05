<?php

namespace FioApi;

class AccountTest extends \PHPUnit_Framework_TestCase
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
        $this->assertEquals('214498596', $account->getAccountNumber());
        $this->assertEquals('2010', $account->getBankCode());
        $this->assertEquals('CZK', $account->getCurrency());
        $this->assertEquals('CZ9820100000000214498596', $account->getIban());
        $this->assertEquals('FIOBCZPPXXX', $account->getBic());
    }
}
