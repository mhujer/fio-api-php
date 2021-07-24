<?php
declare(strict_types = 1);

namespace FioApi\Upload\Entity;

use FioApi\Exceptions\UnexpectedPaymentOrderValueException;

class PaymentOrderCzech extends PaymentOrder
{
    use Symbols;

    public const PAYMENT_TYPE_STANDARD = 431001;
    public const PAYMENT_TYPE_PRIORITY = 431005;
    public const PAYMENT_TYPE_COLLECTION = 431022;

    protected const PAYMENT_TYPES = [self::PAYMENT_TYPE_STANDARD, self::PAYMENT_TYPE_PRIORITY, self::PAYMENT_TYPE_COLLECTION];
    protected const BANK_CODE_NAME = 'bankCode';
    protected const MESSAGE_FOR_RECIPIENT_NAME = 'messageForRecipient';
    protected const MESSAGE_FOR_RECIPIENT_MAX_LENGTH = 140;
    protected const BANK_CODE_LENGTH = 4;

    protected string $bankCode;
    protected string $messageForRecipient;
    protected int $paymentType;

    public function __construct(
        string $currency,
        float $amount,
        string $accountTo,
        string $bankCode,
        \DateTimeInterface $date,
        ?string $constantSymbol = null,
        ?string $variableSymbol = null,
        ?string $specificSymbol = null,
        ?string $messageForRecipient = null,
        ?string $comment = null,
        ?int $paymentReason = null,
        ?int $paymentType = null
    ) {
        parent::__construct($currency, $amount, $accountTo, $date, $comment);

        $this->setBankCode($bankCode);
        if ($constantSymbol !== null) {
            $this->setConstantSymbol($constantSymbol);
        }
        if ($variableSymbol !== null) {
            $this->setVariableSymbol($variableSymbol);
        }
        if ($specificSymbol !== null) {
            $this->setSpecificSymbol($specificSymbol);
        }
        if ($messageForRecipient !== null) {
            $this->setMessageForRecipient($messageForRecipient);
        }
        if ($paymentReason !== null) {
            $this->setPaymentReason($paymentReason);
        }
        if ($paymentType !== null) {
            $this->setPaymentType($paymentType);
        }
    }

    public function toArray(): array {
        return array_merge(
            parent::toArray(),
            [ static::BANK_CODE_NAME => $this->getBankCode() ],
            $this->symbolsToArray(),
            [
                static::DATE_NAME => $this->getDate(),
                static::MESSAGE_FOR_RECIPIENT_NAME => $this->getMessageForRecipient(),
                static::COMMENT_NAME => $this->getComment(),
                static::PAYMENT_REASON_NAME => $this->getPaymentReason(),
                static::PAYMENT_TYPE_NAME => $this->getPaymentType(),
            ]
        );
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    public function getMessageForRecipient(): ?string
    {
        return $this->messageForRecipient ?? null;
    }

    public function getPaymentType(): ?int
    {
        return $this->paymentType ?? null;
    }

    public function getPaymentReason(): ?int
    {
        return $this->paymentReason ?? null;
    }

    /** @return static */
    protected function setBankCode(string $bankCode)
    {
        $this->bankCode = static::validateBankCode($bankCode);
        return $this;
    }

    /** @return static */
    protected function setMessageForRecipient(string $messageForRecipient)
    {
        $this->messageForRecipient = static::validateStringMaxLength(
            $messageForRecipient,
            static::MESSAGE_FOR_RECIPIENT_MAX_LENGTH
        );
        return $this;
    }

    /** @return static */
    protected function setPaymentType(int $type)
    {
        $this->paymentType = static::validateValueIsInList($type, static::PAYMENT_TYPES);
        return $this;
    }

    protected static function validateAccountTo(string $accountTo): string
    {
        if (!preg_match('/^(\d{2,6}-)?\d{2,10}$/', $accountTo)) {
            throw new UnexpectedPaymentOrderValueException(
                'Account has to be in this format: "prefix-base". Prefix is optional and contains 2-6 digits. Base contains 2-10 digits.'
            );
        }
        return $accountTo;
    }

    protected static function validateBankCode(string $code): string
    {
        if (ctype_digit($code) === false) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Bank code "%s" has to contain digits only.', $code)
            );
        }
        if (strlen($code) !== self::BANK_CODE_LENGTH) {
            throw new UnexpectedPaymentOrderValueException(
                sprintf('Bank code "%s" has to contain exactly %s digits.', $code, self::BANK_CODE_LENGTH)
            );
        }
        return $code;
    }
}
