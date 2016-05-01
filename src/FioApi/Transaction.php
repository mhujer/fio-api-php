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
    public static function createFromJson(\stdClass $data)
    {
        $mapColumnToProps = [
            'column22' => 'id',
            'column0' => 'date',
            'column1' => 'amount',
            'column14' => 'currency',
            'column2' => 'accountNumber',
            'column3' => 'bankCode',
            'column12' => 'bankName',
            'column4' => 'constantSymbol',
            'column5' => 'variableSymbol',
            'column6' => 'specificSymbol',
            'column7' => 'userIdentity',
            'column16' => 'userMessage',
            'column8' => 'transactionType',
            'column9' => 'performedBy',
            'column25' => 'comment',
            'column17' => 'paymentOrderId',
            'column18' => 'specification'
        ];

        $newData = new \stdClass();
        foreach ($data as $key => $value) {
            if (isset($mapColumnToProps[$key]) && $value !== NULL) {
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
            !empty($data->id) ? $data->id : NULL,
            $data->date,
            $data->amount,
            $data->currency,
            !empty($data->accountNumber) ? $data->accountNumber : NULL,
            !empty($data->bankCode) ? $data->bankCode : NULL,
            !empty($data->bankName) ? $data->bankName : NULL,
            !empty($data->constantSymbol) ? $data->constantSymbol : NULL,
            !empty($data->variableSymbol) ? $data->variableSymbol : '0',
            !empty($data->specificSymbol) ? $data->specificSymbol : NULL,
            !empty($data->userIdentity) ? $data->userIdentity : NULL,
            !empty($data->userMessage) ? $data->userMessage : NULL,
            !empty($data->transactionType) ? $data->transactionType : NULL,
            !empty($data->performedBy) ? $data->performedBy : NULL,
            !empty($data->comment) ? $data->comment : NULL,
            !empty($data->paymentOrderId) ? $data->paymentOrderId : NULL,
            !empty($data->specification) ? $data->specification : NULL
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
