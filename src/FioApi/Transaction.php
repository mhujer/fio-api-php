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
            $data->column2 !== null ? $data->column2->value : null, //Protiúčet
            $data->column3 !== null ? $data->column3->value : null, //Kód banky
            $data->column12 !== null ? $data->column12->value : null, //Název banky
            $data->column10 !== null ? $data->column10->value : null, //Název protiúčtu
            $data->column4 !== null ? $data->column4->value : null, //KS
            $data->column5 !== null ? $data->column5->value : null, //VS
            $data->column6 !== null ? $data->column6->value : null, //SS
            $data->column7 !== null ? $data->column7->value : null, //Uživatelská identifikace
            $data->column16 !== null ? $data->column16->value : null, //Zpráva pro příjemce
            $data->column8 !== null ? $data->column8->value : '', //Typ
            $data->column9 !== null ? $data->column9->value : null, //Provedl
            $data->column25 !== null ? $data->column25->value : null, //Komentář
            $data->column17 !== null ? $data->column17->value : null, //ID pokynu
            $data->column18 !== null ? $data->column18->value : null //Upřesnění
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
