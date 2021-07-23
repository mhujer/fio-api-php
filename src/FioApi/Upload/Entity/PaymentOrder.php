<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

abstract class PaymentOrder
{
    protected const COMMENT_MAX_LENGTH = 255;
    protected const PAYMENT_REASON_MIN = 100;
    protected const PAYMENT_REASON_MAX = 999;

    protected string $currency;
    protected float $amount;
    protected string $accountTo;
    protected string $date;
    protected string $comment;
    protected int $paymentReason;

    protected function __construct(
        string $currency,
        float $amount,
        string $accountTo,
        \DateTimeInterface $date,
        ?string $comment = null
    ) {
        $this->setCurrency($currency)
            ->setAmount($amount)
            ->setAccountTo($accountTo)
            ->setDate($date);
        if ($comment !== null) {
            $this->setComment($comment);
        }
    }

    public function toArray(): array {
        return [
            'currency' => $this->currency ?? null,
            'amount' => $this->amount ?? null,
            'accountTo' => $this->accountTo ?? null,
            'date' => $this->date ?? null,
            'comment' => $this->comment ?? null,
            'paymentReason' => $this->paymentReason ?? null,
        ];
    }

    /** @return static */
    protected function setCurrency(string $currency)
    {
        if (!preg_match('/[a-z]{3}/i', $currency)) {
            throw new UnexpectedPaymentOrderValueException('Currency code has to match ISO 4217.');
        }
        $this->currency = strtoupper($currency);
        return $this;
    }

    /** @return static */
    protected function setAmount(float $amount)
    {
        if ($amount <= 0) {
            throw new UnexpectedPaymentOrderValueException('Amount has to be positive number.');
        }
        $this->amount = $amount;
        return $this;
    }

    /** @return static */
    protected function setAccountTo(string $accountTo)
    {
        $this->accountTo = static::validateAccountTo($accountTo);
        return $this;
    }

    /** @return static */
    protected function setDate(\DateTimeInterface $date)
    {
        $this->date = $date->format('Y-m-d');
        return $this;
    }

    /** @return static */
    protected function setComment(string $comment)
    {
        $this->comment = static::validateStringMaxLength($comment, static::COMMENT_MAX_LENGTH);
        return $this;
    }

    /** @return static */
    protected function setPaymentReason(int $paymentReason)
    {
        if ($paymentReason < self::PAYMENT_REASON_MIN || $paymentReason > self::PAYMENT_REASON_MAX) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Payment reason "%s" is out of allowed range %s - %s.', $paymentReason, self::PAYMENT_REASON_MIN, self::PAYMENT_REASON_MAX)
            );
        }
        $this->paymentReason = $paymentReason;
        return $this;
    }

    abstract protected static function validateAccountTo(string $accountTo): string;

    protected static function validateStringMaxLength(string $text, int $maxLength): string
    {
        if (mb_strlen($text) > $maxLength) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Value "%s" has to contain %s characters at maximum.', $text, $maxLength)
            );
        }
        return $text;
    }

    protected static function validateValueIsInList(int $value, array $list): int
    {
        if (in_array($value, $list, true) === false) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Value "%s" is out of allowed set [%s].', $value, implode(', ', $list))
            );
        }
        return $value;
    }
}
