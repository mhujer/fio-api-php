<?php
declare(strict_types = 1);

namespace FioApi;

class Transaction
{
    /** @var string */
    protected $id;

    /** @var \DateTimeImmutable */
    protected $date;

    /** @var float */
    protected $amount;

    /** @var string */
    protected $currency;

    /** @var string|null */
    protected $senderAccountNumber;

    /** @var string|null */
    protected $senderBankCode;

    /** @var string|null */
    protected $senderBankName;

    /** @var string|null */
    protected $senderName;

    /** @var string|null */
    protected $constantSymbol;

    /** @var string|null */
    protected $variableSymbol;

    /** @var string|null */
    protected $specificSymbol;

    /** @var string|null */
    protected $userIdentity;

    /** @var string|null */
    protected $userMessage;

    /** @var string */
    protected $transactionType;

    /** @var string|null */
    protected $performedBy;

    /** @var string|null */
    protected $comment;

    /** @var float|null */
    protected $paymentOrderId;

    /** @var string|null */
    protected $specification;

    protected function __construct(
        string $id,
        \DateTimeImmutable $date,
        float $amount,
        string $currency,
        ?string $senderAccountNumber,
        ?string $senderBankCode,
        ?string $senderBankName,
        ?string $senderName,
        ?string $constantSymbol,
        ?string $variableSymbol,
        ?string $specificSymbol,
        ?string $userIdentity,
        ?string $userMessage,
        string $transactionType,
        ?string $performedBy,
        ?string $comment,
        ?float $paymentOrderId,
        ?string $specification
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->senderAccountNumber = $senderAccountNumber;
        $this->senderBankCode = $senderBankCode;
        $this->senderBankName = $senderBankName;
        $this->senderName = $senderName;
        $this->constantSymbol = $constantSymbol;
        $this->variableSymbol = $variableSymbol;
        $this->specificSymbol = $specificSymbol;
        $this->userIdentity = $userIdentity;
        $this->userMessage = $userMessage;
        $this->transactionType = $transactionType;
        $this->performedBy = $performedBy;
        $this->comment = $comment;
        $this->paymentOrderId = $paymentOrderId;
        $this->specification = $specification;
    }

    /**
     * @param \stdClass $data Transaction data from JSON API response
     * @return Transaction
     */
    public static function create(\stdClass $data): Transaction
    {
        return new self(
            (string) $data->column22->value, //ID pohybu
            new \DateTimeImmutable($data->column0->value), //Datum
            $data->column1->value, //Objem
            $data->column14->value, //Měna
            !empty($data->column2) ? $data->column2->value : null, //Protiúčet
            !empty($data->column3) ? $data->column3->value : null, //Kód banky
            !empty($data->column12) ? $data->column12->value : null, //Název banky
            !empty($data->column10) ? $data->column10->value : null, //Název protiúčtu
            !empty($data->column4) ? $data->column4->value : null, //KS
            !empty($data->column5) ? $data->column5->value : null, //VS
            !empty($data->column6) ? $data->column6->value : null, //SS
            !empty($data->column7) ? $data->column7->value : null, //Uživatelská identifikace
            !empty($data->column16) ? $data->column16->value : null, //Zpráva pro příjemce
            !empty($data->column8) ? $data->column8->value : '', //Typ
            !empty($data->column9) ? $data->column9->value : null, //Provedl
            !empty($data->column25) ? $data->column25->value : null, //Komentář
            !empty($data->column17) ? $data->column17->value : null, //ID pokynu
            !empty($data->column18) ? $data->column18->value : null //Upřesnění
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getSenderAccountNumber(): ?string
    {
        return $this->senderAccountNumber;
    }

    public function getSenderBankCode(): ?string
    {
        return $this->senderBankCode;
    }

    public function getSenderBankName(): ?string
    {
        return $this->senderBankName;
    }

    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    public function getConstantSymbol(): ?string
    {
        return $this->constantSymbol;
    }

    public function getVariableSymbol(): ?string
    {
        return $this->variableSymbol;
    }

    public function getSpecificSymbol(): ?string
    {
        return $this->specificSymbol;
    }

    public function getUserIdentity(): ?string
    {
        return $this->userIdentity;
    }

    public function getUserMessage(): ?string
    {
        return $this->userMessage;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getPerformedBy(): ?string
    {
        return $this->performedBy;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getPaymentOrderId(): ?float
    {
        return $this->paymentOrderId;
    }

    public function getSpecification(): ?string
    {
        return $this->specification;
    }
}
