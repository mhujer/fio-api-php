<?php
declare(strict_types = 1);

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

    /** @var float */
    protected $idFrom;

    /** @var float */
    protected $idTo;

    /** @var int */
    protected $idLastDownload;

    /** @var Account */
    protected $account;

    /** @var Transaction[] */
    protected $transactions = [];

    protected function __construct(
        float $openingBalance,
        float $closingBalance,
        \DateTimeImmutable $dateStart,
        \DateTimeImmutable $dateEnd,
        ?float $idFrom,
        ?float $idTo,
        ?int $idLastDownload,
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
    public static function create(\stdClass $data): TransactionList
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

    public function getOpeningBalance(): float
    {
        return $this->openingBalance;
    }

    public function getClosingBalance(): float
    {
        return $this->closingBalance;
    }

    public function getDateStart(): \DateTimeImmutable
    {
        return $this->dateStart;
    }

    public function getDateEnd(): \DateTimeImmutable
    {
        return $this->dateEnd;
    }

    public function getIdFrom(): ?float
    {
        return $this->idFrom;
    }

    public function getIdTo(): ?float
    {
        return $this->idTo;
    }

    public function getIdLastDownload(): ?int
    {
        return $this->idLastDownload;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}
