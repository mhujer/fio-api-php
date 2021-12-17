<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

class PaymentOrderInternational extends PaymentOrderForeign
{
    public const CHARGES_OUR = 470501;
    public const CHARGES_BEN = 470502;
    public const CHARGES_SHA = 470503;

    protected const CHARGES_TYPES = [self::CHARGES_OUR, self::CHARGES_BEN, self::CHARGES_SHA];
    protected const REMITTANCE_INFO_4_NAME = 'remittanceInfo4';
    protected const DETAILS_OF_CHARGES_NAME = 'detailsOfCharges';

    protected int $detailsOfCharges;
    protected string $remittanceInfo4;

    public function __construct(
        string $currency,
        float $amount,
        string $accountTo,
        string $bic,
        \DateTimeInterface $date,
        string $benefName,
        string $benefStreet,
        string $benefCity,
        string $benefCountry,
        int $detailsOfCharges,
        int $paymentReason,
        string $remittanceInfo1,
        ?string $remittanceInfo2 = null,
        ?string $remittanceInfo3 = null,
        ?string $remittanceInfo4 = null,
        ?string $comment = null
    ) {
        parent::__construct($currency, $amount, $accountTo, $date, $benefName, $comment, $remittanceInfo2, $remittanceInfo3);

        $this->setBic($bic)
            ->setBenefStreet($benefStreet)
            ->setBenefCity($benefCity)
            ->setBenefCountry($benefCountry)
            ->setDetailsOfCharges($detailsOfCharges)
            ->setPaymentReason($paymentReason)
            ->setRemittanceInfo1($remittanceInfo1);
        if ($remittanceInfo4 !== null) {
            $this->setRemittanceInfo4($remittanceInfo4);
        }
    }

    public function toArray(): array {
        return array_merge(
            parent::toArray(),
            $this->foreignOrderPropertiesToArray(),
            [
                static::REMITTANCE_INFO_4_NAME => $this->getRemittanceInfo4(),
                static::DETAILS_OF_CHARGES_NAME => $this->getDetailsOfCharges(),
                static::PAYMENT_REASON_NAME => $this->getPaymentReason(),
            ]
        );
    }

    public function getPaymentReason(): int
    {
        return $this->paymentReason;
    }

    public function getBic(): string
    {
        return $this->bic;
    }

    public function getBenefStreet(): string
    {
        return $this->benefStreet;
    }

    public function getBenefCity(): string
    {
        return $this->benefCity;
    }

    public function getBenefCountry(): string
    {
        return $this->benefCountry;
    }

    public function getDetailsOfCharges(): int
    {
        return $this->detailsOfCharges;
    }

    public function getRemittanceInfo1(): string
    {
        return $this->remittanceInfo1;
    }

    public function getRemittanceInfo4(): ?string
    {
        return $this->remittanceInfo4 ?? null;
    }

    /** @return static */
    protected function setDetailsOfCharges(int $detailsOfCharges)
    {
        $this->detailsOfCharges = static::validateValueIsInList($detailsOfCharges, static::CHARGES_TYPES);
        return $this;
    }

    /** @return static */
    protected function setRemittanceInfo4(string $remittanceInfo4)
    {
        $this->remittanceInfo4 = static::validateStringMaxLength($remittanceInfo4, static::REMITTANCE_INFO_MAX_LENGTH);
        return $this;
    }
}
