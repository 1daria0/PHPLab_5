<?php
declare(strict_types=1);

class TransactionManager
{
    private TransactionRepository $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Возвращает общую сумму всех транзакций.
     */
    public function calculateTotalAmount(): float
    {
        $total = 0.0;
        foreach ($this->repository->getAllTransactions() as $transaction) {
            $total += $transaction->getAmount();
        }
        return $total;
    }

    /**
     * Возвращает сумму транзакций за указанный период (включительно).
     * @param string $startDate Начальная дата в формате Y-m-d
     * @param string $endDate Конечная дата в формате Y-m-d
     */
    public function calculateTotalAmountByDateRange(string $startDate, string $endDate): float
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $total = 0.0;
        foreach ($this->repository->getAllTransactions() as $transaction) {
            $transactionDate = $transaction->getDate();
            if ($transactionDate >= $start && $transactionDate <= $end) {
                $total += $transaction->getAmount();
            }
        }
        return $total;
    }

    /**
     * Подсчитывает количество транзакций для указанного получателя.
     */
    public function countTransactionsByMerchant(string $merchant): int
    {
        $count = 0;
        foreach ($this->repository->getAllTransactions() as $transaction) {
            if ($transaction->getMerchant() === $merchant) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Возвращает массив транзакций, отсортированных по дате (от старых к новым).
     */
    public function sortTransactionsByDate(): array
    {
        $transactions = $this->repository->getAllTransactions();
        usort($transactions, function (Transaction $a, Transaction $b) {
            return $a->getDate() <=> $b->getDate();
        });
        return $transactions;
    }

    /**
     * Возвращает массив транзакций, отсортированных по сумме по убыванию.
     */
    public function sortTransactionsByAmountDesc(): array
    {
        $transactions = $this->repository->getAllTransactions();
        usort($transactions, function (Transaction $a, Transaction $b) {
            return $b->getAmount() <=> $a->getAmount();
        });
        return $transactions;
    }
}