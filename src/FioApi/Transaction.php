<?php

namespace FioApi;

class Transaction
{
    const REMITTANCE_INFO_LENGTH = 35;

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
    public static function createFromJson(\stdClass $data)
    {
        $mapColumnToProps = [
            'column22' => 'id',
            'column0'  => 'date',
            'column1'  => 'amount',
            'column14' => 'currency',
            'column2'  => 'accountNumber',
            'column3'  => 'bankCode',
            'column12' => 'bankName',
            'column4'  => 'constantSymbol',
            'column5'  => 'variableSymbol',
            'column6'  => 'specificSymbol',
            'column7'  => 'userIdentity',
            'column16' => 'userMessage',
            'column8'  => 'transactionType',
            'column9'  => 'performedBy',
            'column25' => 'comment',
            'column17' => 'paymentOrderId',
            'column18' => 'specification',
        ];

        $newData = new \stdClass();
        foreach ($data as $key => $value) {
            if (isset($mapColumnToProps[$key]) && $value !== null) {
                $newKey = $mapColumnToProps[$key];
                if ($newKey === 'date') {
                    $newData->{$newKey} = new \DateTime($value->value);
                } else {
                    $newData->{$newKey} = $value->value;
                }
            }
        }

        return self::create($newData);
    }

    public static function create(\stdClass $data)
    {
        return new self(
            !empty($data->id) ? $data->id : null,
            $data->date,
            $data->amount,
            $data->currency,
            !empty($data->accountNumber) ? $data->accountNumber : null,
            !empty($data->bankCode) ? $data->bankCode : null,
            !empty($data->bankName) ? $data->bankName : null,
            !empty($data->constantSymbol) ? $data->constantSymbol : null,
            !empty($data->variableSymbol) ? $data->variableSymbol : '0',
            !empty($data->specificSymbol) ? $data->specificSymbol : null,
            !empty($data->userIdentity) ? $data->userIdentity : null,
            !empty($data->userMessage) ? $data->userMessage : null,
            !empty($data->transactionType) ? $data->transactionType : null,
            !empty($data->performedBy) ? $data->performedBy : null,
            !empty($data->comment) ? $data->comment : null,
            !empty($data->paymentOrderId) ? $data->paymentOrderId : null,
            !empty($data->specification) ? $data->specification : null
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
     *
     * @return string
     */
    public function getSenderAccountNumber()
    {
        trigger_error(__METHOD__.' is deprecated use getAccountNumber() instead.', E_USER_DEPRECATED);

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
     *
     * @return string
     */
    public function getSenderBankCode()
    {
        trigger_error(__METHOD__.' is deprecated use getBankCode() instead.', E_USER_DEPRECATED);

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
     *
     * @return string
     */
    public function getSenderBankName()
    {
        trigger_error(__METHOD__.' is deprecated use getBankName() instead.', E_USER_DEPRECATED);

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
     * Gets first chunk of remittance info.
     *
     * @return string
     */
    public function getRemittanceInfo1()
    {
        return (string) substr($this->getUserMessage(), 0, self::REMITTANCE_INFO_LENGTH);
    }

    /**
     * Gets second chunk of remittance info.
     *
     * @return string
     */
    public function getRemittanceInfo2()
    {
        return (string) substr($this->getUserMessage(), self::REMITTANCE_INFO_LENGTH, self::REMITTANCE_INFO_LENGTH);
    }

    /**
     * Gets third chunk of remittance info.
     *
     * @return string
     */
    public function getRemittanceInfo3()
    {
        return (string) substr($this->getUserMessage(), 2 * self::REMITTANCE_INFO_LENGTH, self::REMITTANCE_INFO_LENGTH);
    }

    /**
     * Gets fourth chunk of remittance info.
     *
     * @return string
     */
    public function getRemittanceInfo4()
    {
        return (string) substr($this->getUserMessage(), 3 * self::REMITTANCE_INFO_LENGTH, self::REMITTANCE_INFO_LENGTH);
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
