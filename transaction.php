<?php
declare(strict_types=1);

class Transaction
{
    private int $id;
    private DateTime $date;
    private float $amount;
    private string $description;
    private string $merchant;

    public function __construct(int $id, DateTime $date, float $amount, string $description, string $merchant)
    {
        $this->id = $id;
        $this->date = $date;
        $this->amount = $amount;
        $this->description = $description;
        $this->merchant = $merchant;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getMerchant(): string
    {
        return $this->merchant;
    }

    /**
     * Возвращает количество дней с момента транзакции до текущего момента.
     */
    public function getDaysSinceTransaction(): int
    {
        $now = new DateTime();
        $interval = $this->date->diff($now);
        return (int)$interval->format('%a');
    }
}