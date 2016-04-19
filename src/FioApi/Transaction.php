<?php

namespace FioApi;

class Transaction
{
    /** @var int */
    protected $id;

    /** @var \DateTime */
    protected $date;

    /** @var float */
    protected $amount;

    /** @var string */
    protected $currency;

    /** @var string */
    protected $accountNumber;

    /** @var string */
    protected $bankCode;

    /** @var string */
    protected $bankName;

    /** @var int */
    protected $constantSymbol;

    /** @var string */
    protected $variableSymbol;

    /** @var int */
    protected $specificSymbol;

    /** @var string */
    protected $userIdentity;

    /** @var string */
    protected $userMessage;

    /** @var string */
    protected $transactionType;

    /** @var string */
    protected $performedBy;

    /** @var string */
    protected $comment;

    /** @var string */
    protected $paymentOrderId;

    /** @var string */
    protected $specification;

    protected function __construct(
        $id,
        $date,
        $amount,
        $currency,
        $accountNumber,
        $bankCode,
        $bankName,
        $constantSymbol,
        $variableSymbol,
        $specificSymbol,
        $userIdentity,
        $userMessage,
        $transactionType,
        $performedBy,
        $comment,
        $paymentOrderId,
        $specification
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->accountNumber = $accountNumber;
        $this->bankCode = $bankCode;
        $this->bankName = $bankName;
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
     *
     * @return Transaction
     */
    public static function create(\stdClass $data)
    {
        return new self(
            $data->column22->value, //ID pohybu
            new \DateTime($data->column0->value), //Datum
            $data->column1->value, //Objem
            $data->column14->value, //Měna
            !empty($data->column2) ? $data->column2->value : null, //Protiúčet
            !empty($data->column3) ? $data->column3->value : null, //Kód banky
            !empty($data->column12) ? $data->column12->value : null, //Název banky
            !empty($data->column4) ? $data->column4->value : null, //KS
            !empty($data->column5) ? $data->column5->value : null, //VS
            !empty($data->column6) ? $data->column6->value : null, //SS
            !empty($data->column7) ? $data->column7->value : null, //Uživatelská identifikace
            !empty($data->column16) ? $data->column16->value : null, //Zpráva pro příjemce
            $data->column8->value, //Typ
            !empty($data->column9) ? $data->column9->value : null, //Provedl
            !empty($data->column25) ? $data->column25->value : null, //Komentář
            !empty($data->column17) ? $data->column17->value : null, //ID pokynu
            !empty($data->column18) ? $data->column18->value : null //Upřesnění
        );
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getSenderAccountNumber()
    {
        trigger_error(__METHOD__ . ' is deprecated use getAccountNumber() instead.', E_USER_DEPRECATED);
        return $this->getAccountNumber();
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getSenderBankCode()
    {
        trigger_error(__METHOD__ . ' is deprecated use getBankCode() instead.', E_USER_DEPRECATED);
        return $this->getBankCode();
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * @deprecated
     * @return string
     */
    public function getSenderBankName()
    {
        trigger_error(__METHOD__ . ' is deprecated use getBankName() instead.', E_USER_DEPRECATED);
        return $this->getBankName();
    }

    /**
     * @return int
     */
    public function getConstantSymbol()
    {
        return $this->constantSymbol;
    }

    /**
     * @return string
     */
    public function getVariableSymbol()
    {
        return $this->variableSymbol;
    }

    /**
     * @return int
     */
    public function getSpecificSymbol()
    {
        return $this->specificSymbol;
    }

    /**
     * @return string
     */
    public function getUserIdentity()
    {
        return $this->userIdentity;
    }

    /**
     * @return string
     */
    public function getUserMessage()
    {
        return $this->userMessage;
    }

    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @return string
     */
    public function getPerformedBy()
    {
        return $this->performedBy;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getPaymentOrderId()
    {
        return $this->paymentOrderId;
    }

    /**
     * @return string
     */
    public function getSpecification()
    {
        return $this->specification;
    }
}
