<?php
declare(strict_types=1);

final class TransactionTableRenderer
{
    /**
     * Возвращает HTML-таблицу с данными о транзакциях.
     *
     * @param Transaction[] $transactions
     * @return string
     */
    public function render(array $transactions): string
    {
        $html = '<table border="1" cellpadding="5" cellspacing="0">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>ID</th>';
        $html .= '<th>Дата</th>';
        $html .= '<th>Сумма</th>';
        $html .= '<th>Описание</th>';
        $html .= '<th>Получатель</th>';
        $html .= '<th>Категория получателя</th>';
        $html .= '<th>Дней с момента транзакции</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($transactions as $transaction) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars((string)$transaction->getId()) . '</td>';
            $html .= '<td>' . $transaction->getDate()->format('Y-m-d') . '</td>';
            $html .= '<td>' . number_format($transaction->getAmount(), 2) . '</td>';
            $html .= '<td>' . htmlspecialchars($transaction->getDescription()) . '</td>';
            $html .= '<td>' . htmlspecialchars($transaction->getMerchant()) . '</td>';
            $html .= '<td>' . htmlspecialchars($this->getCategory($transaction->getMerchant())) . '</td>';
            $html .= '<td>' . $transaction->getDaysSinceTransaction() . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Определяет категорию получателя (простая логика для примера).
     */
    private function getCategory(string $merchant): string
    {
        if (stripos($merchant, 'магазин') !== false) {
            return 'Retail';
        } elseif (stripos($merchant, 'кафе') !== false || stripos($merchant, 'ресторан') !== false) {
            return 'Food & Drink';
        } elseif (stripos($merchant, 'транспорт') !== false) {
            return 'Transport';
        } else {
            return 'Other';
        }
    }
}