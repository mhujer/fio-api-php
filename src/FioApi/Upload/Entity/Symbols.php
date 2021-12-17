<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

trait Symbols
{
    protected string $constantSymbol;
    protected string $variableSymbol;
    protected string $specificSymbol;

    protected function symbolsToArray(): array {
        return [
            'ks' => $this->getConstantSymbol(),
            'vs' => $this->getVariableSymbol(),
            'ss' => $this->getSpecificSymbol(),
        ];
    }

    public function getConstantSymbol(): ?string
    {
        return $this->constantSymbol ?? null;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol ?? null;
    }

    public function getSpecificSymbol(): ?string
    {
        return $this->specificSymbol ?? null;
    }

    /** @return static */
    protected function setConstantSymbol(string $constantSymbol)
    {
        $this->constantSymbol = static::validateSymbol($constantSymbol, 4);
        return $this;
    }

    /** @return static */
    protected function setVariableSymbol(string $variableSymbol)
    {
        $this->variableSymbol = static::validateSymbol($variableSymbol, 10);
        return $this;
    }

    /** @return static */
    protected function setSpecificSymbol(string $specificSymbol)
    {
        $this->specificSymbol = static::validateSymbol($specificSymbol, 10);
        return $this;
    }

    protected static function validateSymbol(string $symbol, int $maxLength): string
    {
        if (ctype_digit($symbol) === false) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Symbol "%s" has to contain digits only.', $symbol)
            );
        }
        if (strlen($symbol) > $maxLength) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Symbol "%s" has to contain %s digits at maximum.', $symbol, $maxLength)
            );
        }
        return $symbol;
    }
}
