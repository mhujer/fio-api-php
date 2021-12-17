<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

abstract class PaymentOrderForeign extends PaymentOrder
{
    protected const BIC_NAME = 'bic';
    protected const BENEF_NAME_NAME = 'benefName';
    protected const BENEF_STREET_NAME = 'benefStreet';
    protected const BENEF_CITY_NAME = 'benefCity';
    protected const BENEF_COUNTRY_NAME = 'benefCountry';
    protected const REMITTANCE_INFO_1_NAME = 'remittanceInfo1';
    protected const REMITTANCE_INFO_2_NAME = 'remittanceInfo2';
    protected const REMITTANCE_INFO_3_NAME = 'remittanceInfo3';

    protected const COMMENT_MAX_LENGTH = 140;
    protected const ACCOUNT_MAX_LENGTH = 34;
    protected const BIC_LENGTH = 11;
    protected const BENEF_NAME_MAX_LENGTH = 35;
    protected const BENEF_STREET_MAX_LENGTH = 35;
    protected const BENEF_CITY_MAX_LENGTH = 35;
    protected const BENEF_COUNTRY_MAX_LENGTH = 3;
    protected const REMITTANCE_INFO_MAX_LENGTH = 35;

    protected string $bic;
    protected string $benefName;
    protected string $benefStreet;
    protected string $benefCity;
    protected string $benefCountry;
    protected string $remittanceInfo1;
    protected string $remittanceInfo2;
    protected string $remittanceInfo3;

    protected function __construct(
        string $currency,
        float $amount,
        string $accountTo,
        \DateTimeInterface $date,
        string $benefName,
        ?string $comment = null,
        ?string $remittanceInfo2 = null,
        ?string $remittanceInfo3 = null
    ) {
        parent::__construct($currency, $amount, $accountTo, $date, $comment);

        $this->setBenefName($benefName);
        if ($remittanceInfo2 !== null) {
            $this->setRemittanceInfo2($remittanceInfo2);
        }
        if ($remittanceInfo3 !== null) {
            $this->setRemittanceInfo3($remittanceInfo3);
        }
    }

    protected function foreignOrderPropertiesToArray(): array {
        return [
            static::BIC_NAME => $this->getBic(),
            static::DATE_NAME => $this->getDate(),
            static::COMMENT_NAME => $this->getComment(),
            static::BENEF_NAME_NAME => $this->getBenefName(),
            static::BENEF_STREET_NAME => $this->getBenefStreet(),
            static::BENEF_CITY_NAME => $this->getBenefCity(),
            static::BENEF_COUNTRY_NAME => $this->getBenefCountry(),
            static::REMITTANCE_INFO_1_NAME => $this->getRemittanceInfo1(),
            static::REMITTANCE_INFO_2_NAME => $this->getRemittanceInfo2(),
            static::REMITTANCE_INFO_3_NAME => $this->getRemittanceInfo3(),
        ];
    }

    public abstract function getBic(): ?string;

    public abstract function getBenefStreet(): ?string;

    public abstract function getBenefCity(): ?string;

    public abstract function getBenefCountry(): ?string;

    public abstract function getRemittanceInfo1(): ?string;

    public function getBenefName(): string
    {
        return $this->benefName;
    }

    public function getRemittanceInfo2(): ?string
    {
        return $this->remittanceInfo2 ?? null;
    }

    public function getRemittanceInfo3(): ?string
    {
        return $this->remittanceInfo3 ?? null;
    }

    /** @return static */
    protected function setBic(string $bic)
    {
        $this->bic = static::validateBic($bic);
        return $this;
    }

    /** @return static */
    protected function setBenefName(string $benefName)
    {
        $this->benefName = static::validateStringMaxLength($benefName, static::BENEF_NAME_MAX_LENGTH);
        return $this;
    }

    /** @return static */
    protected function setBenefStreet(string $benefStreet)
    {
        $this->benefStreet = static::validateStringMaxLength($benefStreet, static::BENEF_STREET_MAX_LENGTH);
        return $this;
    }

    /** @return static */
    protected function setBenefCity(string $benefCity)
    {
        $this->benefCity = static::validateStringMaxLength($benefCity, static::BENEF_CITY_MAX_LENGTH);
        return $this;
    }

    /** @return static */
    protected function setBenefCountry(string $benefCountry)
    {
        $this->benefCountry = static::validateBenefCountry($benefCountry);
        return $this;
    }

    /** @return static */
    protected function setRemittanceInfo1(string $remittanceInfo1)
    {
        $this->remittanceInfo1 = static::validateStringMaxLength($remittanceInfo1, static::REMITTANCE_INFO_MAX_LENGTH);
        return $this;
    }

    /** @return static */
    protected function setRemittanceInfo2(string $remittanceInfo2)
    {
        $this->remittanceInfo2 = static::validateStringMaxLength($remittanceInfo2, static::REMITTANCE_INFO_MAX_LENGTH);
        return $this;
    }

    /** @return static */
    protected function setRemittanceInfo3(string $remittanceInfo3)
    {
        $this->remittanceInfo3 = static::validateStringMaxLength($remittanceInfo3, static::REMITTANCE_INFO_MAX_LENGTH);
        return $this;
    }

    protected static function validateAccountTo(string $accountTo): string
    {
        return static::validateStringMaxLength($accountTo, static::ACCOUNT_MAX_LENGTH);
    }

    protected static function validateBic(string $bic): string
    {
        if (ctype_alnum($bic) === false) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('BIC "%s" has to contain alphanumeric characters only.', $bic)
            );
        }
        if (strlen($bic) !== self::BIC_LENGTH) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('BIC "%s" has to contain exactly %s characters.', $bic, self::BIC_LENGTH)
            );
        }
        return $bic;
    }

    protected static function validateBenefCountry(string $benefCountry): string
    {
        if (ctype_alnum($benefCountry) === false) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Benef country "%s" has to contain alphanumeric characters only.', $benefCountry)
            );
        }
        if (strlen($benefCountry) > self::BENEF_COUNTRY_MAX_LENGTH) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Benef country "%s" has to contain %s characters at maximum.', $benefCountry, self::BENEF_COUNTRY_MAX_LENGTH)
            );
        }
        return $benefCountry;
    }
}
