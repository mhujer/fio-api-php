<?php
namespace FioApi;

class TransactionList
{
    /** @var float */
    protected $openingBalance;

    /** @var float */
    protected $closingBalance;

    /** @var \DateTimeImmutable */
    protected $dateStart;

    /** @var \DateTimeImmutable */
    protected $dateEnd;

    /** @var int */
    protected $idFrom;

    /** @var int */
    protected $idTo;

    /** @var int */
    protected $idLastDownload;

    /** @var Account */
    protected $account;

    /** @var Transaction[] */
    protected $transactions = [];

    /**
     * @param float $openingBalance
     * @param float $closingBalance
     * @param \DateTimeImmutable $dateStart
     * @param \DateTimeImmutable $dateEnd
     * @param int $idFrom
     * @param int $idTo
     * @param int $idLastDownload
     * @param Account $account
     */
    protected function __construct(
        $openingBalance,
        $closingBalance,
        \DateTimeImmutable $dateStart,
        \DateTimeImmutable $dateEnd,
        $idFrom,
        $idTo,
        $idLastDownload,
        Account $account
    ) {
        $this->openingBalance = $openingBalance;
        $this->closingBalance = $closingBalance;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->idFrom = $idFrom;
        $this->idTo = $idTo;
        $this->idLastDownload = $idLastDownload;
        $this->account = $account;
    }

    /**
     * @param Transaction $transaction
     */
    protected function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @param \stdClass $data Data from JSON API response
     * @return TransactionList
     */
    public static function create(\stdClass $data)
    {
        $account = new Account(
            $data->info->accountId,
            $data->info->bankId,
            $data->info->currency,
            $data->info->iban,
            $data->info->bic
        );

        $transactionList = new self(
            $data->info->openingBalance,
            $data->info->closingBalance,
            new \DateTimeImmutable($data->info->dateStart),
            new \DateTimeImmutable($data->info->dateEnd),
            $data->info->idFrom,
            $data->info->idTo,
            $data->info->idLastDownload,
            $account
        );

        foreach ($data->transactionList->transaction as $transaction) {
            $transactionList->addTransaction(Transaction::create($transaction));
        }

        return $transactionList;
    }

    /**
     * @return float
     */
    public function getOpeningBalance()
    {
        return $this->openingBalance;
    }

    /**
     * @return float
     */
    public function getClosingBalance()
    {
        return $this->closingBalance;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @return int
     */
    public function getIdFrom()
    {
        return $this->idFrom;
    }

    /**
     * @return int
     */
    public function getIdTo()
    {
        return $this->idTo;
    }

    /**
     * @return int
     */
    public function getIdLastDownload()
    {
        return $this->idLastDownload;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }
}
